<?php

namespace App\Actions\Role;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetRolesAction
{
    /**
     * Get paginated roles with filters.
     */
    public function execute(Request $request): LengthAwarePaginator
    {
        $sortBy = $request->get('sortBy', 'created_at');
        $sortDirection = $request->get('sortDirection', 'desc');
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        return Role::withCount(['users as total', 'permissions as access'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy($sortBy, $sortDirection)
            ->paginate($perPage)
            ->withQueryString();
    }
}
