<?php

namespace MrugeshTatvasoft\LaravelAnalytics\Filters;

use Illuminate\Support\Str;
use MrugeshTatvasoft\LaravelAnalytics\AnalyticsDimensions;
use MrugeshTatvasoft\LaravelAnalytics\AnalyticsMetrics;
use MrugeshTatvasoft\LaravelAnalytics\Exceptions\InvalidDimensionException;
use MrugeshTatvasoft\LaravelAnalytics\Exceptions\InvalidMetricException;

abstract class AnalyticsFilter
{
    public string $subject = '';

    public string $type = '';

    public function setDimension(string $subject): static
    {
        if (! Str::contains($subject, ':') && ! in_array($subject, AnalyticsDimensions::getAvailableDimensions())) {
            throw new InvalidDimensionException($subject.' is not a valid dimension or custom dimension for GA4');
        }

        $this->subject = $subject;
        $this->type = 'dimension';

        return $this;
    }

    public function setMetric(string $subject): static
    {
        if (! Str::contains($subject, ':') && ! in_array($subject, AnalyticsMetrics::getAvailableMetrics())) {
            throw new InvalidMetricException($subject.' is not a valid dimension or custom dimension for GA4');
        }

        $this->subject = $subject;
        $this->type = 'metric';

        return $this;
    }

    abstract public function getGoogleFilterType();

    abstract public function toGoogleTypes();

    abstract public function toArray();
}
