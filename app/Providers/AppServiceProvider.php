<?php

namespace App\Providers;

use App\Models\ContactRequest;
use App\Observers\ContactRequestObserver;
use Illuminate\Support\ServiceProvider;
use App\Models\Customer;
use App\Observers\CustomerObserver;
use Illuminate\Support\Facades\URL;

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
        URL::forceScheme('https');
        Customer::observe(CustomerObserver::class);
        ContactRequest::observe(ContactRequestObserver::class);

    }
}
