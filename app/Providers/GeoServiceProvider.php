<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use OrangeShadow\Payments\Services\Geo\Client\GeoHelperClient;
use OrangeShadow\Payments\Services\Geo\GeoHelperService;
use OrangeShadow\Payments\Services\Geo\GeoInterface;

class GeoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(GeoInterface::class, function ($app) {
            return new GeoHelperService(new GeoHelperClient(), env('GEOHELPER_API_KEY'));
        });
    }
}
