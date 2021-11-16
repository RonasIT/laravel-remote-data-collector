<?php

namespace RonasIT\Support\DataCollectors;

use Illuminate\Support\ServiceProvider;

class RemoteDataCollectorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/remote-data-collector.php' => config_path('remote-data-collector.php'),
        ], 'config');
    }

    public function register()
    {
    }
}