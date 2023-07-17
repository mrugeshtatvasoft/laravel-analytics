<?php

namespace MrugeshTatvasoft\LaravelAnalytics\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MrugeshTatvasoft\LaravelAnalytics\LaravelAnalytics
 */
class LaravelAnalytics extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \MrugeshTatvasoft\LaravelAnalytics\LaravelAnalytics::class;
    }
}
