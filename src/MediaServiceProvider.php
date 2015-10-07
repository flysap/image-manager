<?php

namespace Flysap\Media;

use Cartalyst\Tags\TagsServiceProvider;
use Illuminate\Support\ServiceProvider;
use Flysap\Support;
use Parfumix\Imageonfly\ImageOnFlyServiceProvider;
use Robbo\Presenter\PresenterServiceProvider;

class MediaServiceProvider extends ServiceProvider {

    public function boot() {
        $this->loadRoutes()
            ->loadViews()
            ->loadConfiguration()
            ->registerMenu();

        $this->publishes([
            __DIR__.'/../configuration' => config_path('yaml/media-manager'),
            __DIR__.'/../migrations/' => database_path('migrations'),
        ]);
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
     * Load views.
     *
     * @return $this
     */
    protected function loadViews() {
        $this->loadViewsFrom(__DIR__ . '/../views', 'scaffold');

        $this->publishes([
            __DIR__ . '/../views' => base_path('resources/views/vendor/scaffold'),
        ]);

        return $this;
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
            __DIR__ . '/../configuration/general.yaml' , 'media-manager'
        );

        Support\merge_yaml_config_from(
            config_path('yaml/media-manager/general.yaml') , 'media-manager'
        );

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
            ImageOnFlyServiceProvider::class,
            TagsServiceProvider::class,
            PresenterServiceProvider::class
        ];

        array_walk($dependencies, function($dependency) {
            app()->register($dependency);
        });

        return $this;
    }

}