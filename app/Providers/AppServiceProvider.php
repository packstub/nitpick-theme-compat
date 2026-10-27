<?php

namespace App\Providers;

use App\Lab\Lab;
use App\Lab\LabThemes;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Lab::registerProviders($this->app);
    }

    public function boot(): void
    {
        LabThemes::register();
    }
}
