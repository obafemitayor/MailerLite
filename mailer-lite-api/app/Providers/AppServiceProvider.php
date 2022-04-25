<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interface\SubscriberProvider;
use App\Implementation\AppSubscriberProvider;
use App\Implementation\MockSubscriberProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SubscriberProvider::class, AppSubscriberProvider::class);
        //Uncomment For Unit Tests
        //$this->app->bind(SubscriberProvider::class, MockSubscriberProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
