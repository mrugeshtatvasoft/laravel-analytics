<?php

namespace MrugeshTatvasoft\AnalyticsV4\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MrugeshTatvasoft\AnalyticsV4\AnalyticsV4
 */
class AnalyticsV4 extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \MrugeshTatvasoft\AnalyticsV4\AnalyticsV4::class;
    }
}
