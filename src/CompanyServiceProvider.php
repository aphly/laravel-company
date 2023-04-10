<?php

namespace Aphly\LaravelCompany;

use Aphly\Laravel\Providers\ServiceProvider;

class CompanyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */

    public function register()
    {
		$this->mergeConfigFrom(
            __DIR__.'/config/company.php', 'company'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/company.php' => config_path('company.php'),
            __DIR__.'/public' => public_path('static/company')
        ]);
        $this->loadViewsFrom(__DIR__.'/views', 'laravel-company');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }

}
