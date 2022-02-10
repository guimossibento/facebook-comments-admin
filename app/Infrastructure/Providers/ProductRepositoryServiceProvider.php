<?php

namespace App\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

class ProductRepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            'App\Infrastructure\Repositories\ProductRepositoryInterface',
            'App\Infrastructure\Repositories\ProductRepository'
        );
    }
}
