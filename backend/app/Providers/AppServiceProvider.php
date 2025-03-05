<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
       /**
         * Register Helper Functions
         */
        require_once app_path('Helpers/paginateItemLength.php');
        require_once app_path('Helpers/queryBuilder.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {   
        $this->register();
    }
}
