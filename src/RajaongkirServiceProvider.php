<?php

namespace Halalsoft\LaravelRajaongkir;

use Illuminate\Support\ServiceProvider;

class RajaongkirServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
//        $this->publishes(
//            [
//                __DIR__.'/../config/select2.php' => config_path('select2.php'),
//            ],
//            'config'
//        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'halalsoft.laravel-rajaongkir',
            function () {
                return $this->app->make('Halalsoft\LaravelRajaongkir\Rajaongkir');
            }
        );
        $this->app->alias('rajaongkir', Rajaongkir::class);

        $this->app->bind(
            'rajaongkir.wrapper',
            function ($app) {
                return new Rajaongkir();
            }
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'halalsoft.laravel-rajaongkir',
        ];
    }
}
