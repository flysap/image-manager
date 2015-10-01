<?php

namespace Flysap\ImageManager;

use Illuminate\Support\ServiceProvider;
use Flysap\Support;

class ImageManagerServiceProvider extends ServiceProvider {

    public function boot() {
        $this->loadRoutes();

        $this->publishes([
            __DIR__.'/../configuration' => config_path('yaml/application'),
        ]);

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->registerDependencies()
            ->loadConfiguration();
    }

    /**
     * Load routes .
     *
     * @return $this
     */
    protected function loadRoutes() {
        /** Register routes . */
        if (! $this->app->routesAreCached())
            require __DIR__.'/../routes.php';

        return $this;
    }

    /**
     * Load configuration .
     *
     * @return $this
     */
    protected function loadConfiguration() {
        Support\set_config_from_yaml(
            __DIR__ . '/../configuration/general.yaml' , 'administrator'
        );

        Support\merge_yaml_config_from(
            config_path('yaml/application/general.yaml') , 'administrator'
        );

        return $this;
    }

    /**
     * Register service provider dependencies .
     *
     */
    protected function registerDependencies() {
        $dependencies = [

        ];

        array_walk($dependencies, function($dependency) {
            app()->register($dependency);
        });

        return $this;
    }

}