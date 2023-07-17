<?php

namespace MkTatva\AnalyticsV4;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class AnalyticsV4ServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-analytics')
            ->hasConfigFile('analytics-v4');
    }

    public function registeringPackage()
    {
        $this->app->bind(AnalyticsV4::class, function () {
            return AnalyticsV4Factory::createFromConfiguration(config('analytics-v4'));
        });

        $this->app->alias(AnalyticsV4::class, 'laravel-analytics');
    }
}
