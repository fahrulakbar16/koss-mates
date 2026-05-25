<?php

namespace App\Http\Controllers;

use App\Actions\BoardingHouse\GetBoardingHouseById;
use App\Actions\BoardingHouse\GetBoardingHousePaginate;
use App\Actions\BoardingHouse\GetBoardingHouseWithRoomUser;
use App\Actions\Welcome\GetWelcomeDataAction;
use App\Http\Resources\BoardingHouse\BoardingHouseDetailResource;
use App\Http\Resources\BoardingHouse\BoardingHouseResource;
use App\Models\BoardingHouse;
use App\Models\Cluster;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    /**
     * Display the welcome page.
     */
    public function index(Request $request)
    {
        $data = app(GetWelcomeDataAction::class)->execute();


        return Inertia::render('Welcome', $data);
    }

    /**
     * Display the boarding house detail page (public, no auth required).
     */
    public function show(BoardingHouse $boardingHouse)
    {
        $boardingHouse = app(GetBoardingHouseWithRoomUser::class)->execute($boardingHouse->id);

        return Inertia::render('BoardingHouseDetail', [
            'boardingHouse' => (new BoardingHouseDetailResource($boardingHouse))->resolve(),
        ]);
    }

    /**
     * Display the boarding houses list page (public, no auth required).
     */
    public function list(Request $request)
    {
        $boardingHouse = app(GetBoardingHousePaginate::class)->execute($request->all(), auth()->user());

        // dd($boardingHouse->toArray());
        $cluster = Cluster::select('id', 'name')->orderBy('name')->get();

        return Inertia::render('BoardingHouses/Index', [
            'data' => BoardingHouseResource::collection($boardingHouse)->response()->getData(true),
            'cluster' => $cluster,
        ]);
    }
}
