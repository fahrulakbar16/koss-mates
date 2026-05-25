<?php

namespace App\Providers;

use App\Models\Transaction;
use App\Policies\Transaction\TransactionPolicy;
use App\Policies\UserRoomPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register Transaction policies as gates
        Gate::define('createBooking', [TransactionPolicy::class, 'createBooking']);
        Gate::define('createExtend', [TransactionPolicy::class, 'createExtend']);

        // Register UserRoom policies as gates
        Gate::define('accessRoomActive', [UserRoomPolicy::class, 'accessRoomActive']);
    }
}
