<?php

namespace App\Providers;

use App\Rate;
use Illuminate\Support\ServiceProvider;
use OrangeShadow\Payments\Contracts\BankInterface;
use OrangeShadow\Payments\Core\Bank;

class BankServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BankInterface::class, function ($app) {
            return new Bank(new Rate());
        });
    }
}
