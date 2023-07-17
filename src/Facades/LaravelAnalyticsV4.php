<?php

namespace MkTatva\AnalyticsV4\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MkTatva\AnalyticsV4\AnalyticsV4
 */
class AnalyticsV4 extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \MkTatva\AnalyticsV4\AnalyticsV4::class;
    }
}
