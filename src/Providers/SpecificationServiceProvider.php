<?php

namespace DangerWayne\Specification\Providers;

use DangerWayne\Specification\Specifications\Builders\SpecificationBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class SpecificationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/specification.php', 'specification');

        $this->app->bind('specification', function () {
            return new SpecificationBuilder;
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/specification.php' => config_path('specification.php'),
            ], 'specification-config');
        }

        $this->registerMacros();
    }

    private function registerMacros(): void
    {
        // Add Collection macro
        Collection::macro('whereSpecification', function ($specification) {
            $collection = $this;

            return $collection->filter(function ($item) use ($specification) {
                return $specification->isSatisfiedBy($item);
            });
        });

        // Add Builder macro
        Builder::macro('whereSpecification', function ($specification) {
            $builder = $this;

            return $specification->toQuery($builder);
        });
    }
}
