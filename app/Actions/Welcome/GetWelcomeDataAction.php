<?php

namespace App\Actions\Welcome;

use App\Models\BoardingHouse;

class GetWelcomeDataAction
{
    /**
     * Get welcome page data including featured and recommended boarding houses.
     */
    public function execute(): array
    {
        // Get featured boarding house for hero section
        $featuredBoardingHouse = BoardingHouse::with(['images', 'cluster'])
            ->inRandomOrder()
            ->first();

        // Get recommended boarding houses
        $recommendedBoardingHouses = BoardingHouse::with(['images', 'cluster', 'rooms.prices'])
            ->withCount('rooms')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get()
            ->map(function ($boardingHouse) {
                // Get minimum price from all room prices
                $minPrice = $boardingHouse->rooms
                    ->flatMap(function ($room) {
                        return $room->prices->pluck('price');
                    })
                    ->min();

                return [
                    'id' => $boardingHouse->id,
                    'name' => $boardingHouse->name,
                    'address' => $boardingHouse->address,
                    'thumbnail' => $boardingHouse->thumbnail ? asset('storage/' . $boardingHouse->thumbnail) : null,
                    'first_image' => $boardingHouse->images->first() ? asset('storage/' . $boardingHouse->images->first()->image) : null,
                    'cluster' => $boardingHouse->cluster ? $boardingHouse->cluster->name : null,
                    'rooms_count' => $boardingHouse->rooms_count,
                    'min_price' => $minPrice ? (float) $minPrice : 0,
                ];
            });

        // Format featured boarding house
        $featured = null;
        if ($featuredBoardingHouse) {
            $featured = [
                'id' => $featuredBoardingHouse->id,
                'name' => $featuredBoardingHouse->name,
                'address' => $featuredBoardingHouse->address,
                'cluster' => $featuredBoardingHouse->cluster ? $featuredBoardingHouse->cluster->name : null,
                'hero_image' => $featuredBoardingHouse->images->first()
                    ? asset('storage/' . $featuredBoardingHouse->images->first()->image)
                    : ($featuredBoardingHouse->thumbnail ? asset('storage/' . $featuredBoardingHouse->thumbnail) : null),
            ];
        }

        return [
            'featuredBoardingHouse' => $featured,
            'recommendedBoardingHouses' => $recommendedBoardingHouses,
        ];
    }
}
