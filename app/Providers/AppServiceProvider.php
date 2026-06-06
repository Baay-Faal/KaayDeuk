<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Bien;
use App\Models\Client;
use App\Models\Visite;
use App\Models\Transaction;
use App\Policies\BienPolicy;
use App\Policies\ClientPolicy;
use App\Policies\VisitePolicy;
use App\Policies\TransactionPolicy;

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
        // Enregistrement des Policies
        Gate::policy(Bien::class, BienPolicy::class);
        Gate::policy(Client::class, ClientPolicy::class);
        Gate::policy(Visite::class, VisitePolicy::class);
        Gate::policy(Transaction::class, TransactionPolicy::class);
    }
}