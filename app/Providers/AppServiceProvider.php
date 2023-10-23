<?php

namespace App\Providers;

use App\Services\ElasticsearchHelperService;
use App\Services\RedisHelperService;
use App\Utilities\Contracts\ElasticsearchHelperInterface;
use App\Utilities\Contracts\RedisHelperInterface;
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
        $this->app->bind(ElasticsearchHelperInterface::class, ElasticsearchHelperService::class);
        $this->app->bind(RedisHelperInterface::class, RedisHelperService::class);
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
