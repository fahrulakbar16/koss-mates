<?php

namespace App\Http\Controllers;

use App\Actions\Dashboard\GetDashboardMetricsAction;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Middleware;

class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth:sanctum',
        ];
    }


    public function index()
    {
        $user = Auth::user();

        // Check if user has Penyewa role
        if ($user && $user->getRoleNames()->contains('Penyewa')) {
            // Stats and Activities are not needed for redirect
            session()->reflash();

            return redirect()->route('penyewa.rooms.index');
        }

        // For non-Penyewa users, render the admin dashboard
        $clusterId = request()->query('cluster_id');
        $metrics = app(GetDashboardMetricsAction::class)->execute($clusterId, $user);

        return Inertia::render('Admin/Dashboard', [
            'metrics' => $metrics,
        ]);
    }
}
