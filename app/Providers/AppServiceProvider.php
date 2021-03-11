<?php

namespace App\Providers;

use Inertia\Inertia;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //https://stackoverflow.com/questions/58513228/why-are-my-props-empty-in-my-laravel-vue-inertia-project
        Inertia::share('flash', function () {
            return [
                'warningLoginMessage' => \Session::get('warningLoginMessage'),                
                'successMessage' => \Session::get('successMessage'),
                'errorMessage' => \Session::get('errorMessage'),
            ];
        });
    }
}
