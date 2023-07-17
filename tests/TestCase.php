<?php

namespace MrugeshTatvasoft\LaravelAnalytics\Tests;

use MrugeshTatvasoft\LaravelAnalytics\LaravelAnalyticsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelAnalyticsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
    }
}
