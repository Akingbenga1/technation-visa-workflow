<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\ApplicationFlowServiceInterface;
use App\Services\ApplicationFlowService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(ApplicationFlowServiceInterface::class, ApplicationFlowService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
