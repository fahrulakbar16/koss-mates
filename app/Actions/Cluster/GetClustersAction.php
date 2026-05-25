<?php

namespace App\Actions\Cluster;

use App\Models\Cluster;
use App\Models\User;
use Illuminate\Http\Request;

class GetClustersAction
{
    /**
     * Get cursor paginated clusters with filters.
     */
    public function execute(Request $request, User $user): array
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $cursor = $request->input('cursor');
        $isPemilik = $user->hasRole('Pemilik');

        $query = Cluster::with(['boardingHouses' => function ($query) use ($isPemilik, $user) {
            $query->with('images', 'owner')
                ->when($isPemilik, function ($query) use ($user) {
                    $query->where('owner_id', $user->id);
                })
                ->withCount('rooms')
                ->orderBy('created_at', 'desc');
        }])
            ->when($isPemilik, function ($query) use ($user) {
                $query->whereHas('boardingHouses', function ($query) use ($user) {
                    $query->where('owner_id', $user->id);
                });
            })
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->withCount('boardingHouses')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc'); // Secondary sort for consistent cursor

        $clusters = $cursor
            ? $query->cursorPaginate($perPage, ['*'], 'cursor', $cursor)
            : $query->cursorPaginate($perPage);

        return [
            'clusters' => $clusters,
        ];
    }
}
