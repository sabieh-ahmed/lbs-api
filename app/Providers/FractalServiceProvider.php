<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;
use \League\Fractal\Serializer\ArraySerializer;

class FractalServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'League\Fractal\Manager',
            function ($app) {
                $manager = new Manager;
                // Use the serializer of your choice.
                $serializer = new ArraySerializer();
                $manager->setSerializer($serializer);
                return $manager;
            }
        );

        $this->app->bind(
            'Scope',
            function ($app) {
                return [];
            }
        );
    }
}
