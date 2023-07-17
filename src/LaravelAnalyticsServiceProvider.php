<?php

namespace MrugeshTatvasoft\LaravelAnalytics;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelAnalyticsServiceProvider extends PackageServiceProvider
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
        $this->app->bind(LaravelAnalytics::class, function () {
            return LaravelAnalyticsFactory::createFromConfiguration(config('analytics-v4'));
        });

        $this->app->alias(LaravelAnalytics::class, 'laravel-analytics');
    }
}
