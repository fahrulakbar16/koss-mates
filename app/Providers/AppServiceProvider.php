<?php

namespace App\Providers;

use App\Models\Expense;
use App\Models\payment;
use App\Models\Transaction;
use App\Observers\ExpenseObserver;
use App\Observers\PaymentObserver;
use App\Policies\Transaction\TransactionPolicy;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ClientInterface::class, Client::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Expense::observe(ExpenseObserver::class);
        payment::observe(PaymentObserver::class);

        Gate::policy(Transaction::class, TransactionPolicy::class);
    }
}
