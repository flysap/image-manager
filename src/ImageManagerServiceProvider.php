<?php

namespace Flysap\ImageManager;

use Eloquent\ImageAble\ImageAbleServiceProvider;
use Illuminate\Support\ServiceProvider;
use Flysap\Support;

class ImageManagerServiceProvider extends ServiceProvider {

    public function boot() {
        $this->loadRoutes()
            ->registerMenu();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->registerDependencies();
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
     * Register menu .
     *
     */
    protected function registerMenu() {
        $menuManager = app('menu-manager');

        $menuManager->addNamespace(realpath(__DIR__ . '/../'), true);
    }

    /**
     * Register service provider dependencies .
     *
     */
    protected function registerDependencies() {
        $dependencies = [
            ImageAbleServiceProvider::class
        ];

        array_walk($dependencies, function($dependency) {
            app()->register($dependency);
        });

        return $this;
    }

}