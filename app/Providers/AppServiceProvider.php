<?php

namespace App\Providers;

use App\Models\User;
use App\Models\WalletTransaction;
use App\Observers\UserObserver;
use App\Observers\WalletTransactionObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        WalletTransaction::observe(WalletTransactionObserver::class);
    }
}
