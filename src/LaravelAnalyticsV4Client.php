<?php

namespace MkTatva\AnalyticsV4;

use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;

class AnalyticsV4Client
{
    protected string $propertyId;

    protected BetaAnalyticsDataClient $client;

    public function setProperty(string $propertyId): AnalyticsV4Client
    {
        $this->propertyId = $propertyId;

        return $this;
    }

    public function setGoogleClient(BetaAnalyticsDataClient $client)
    {
        $this->client = $client;

        return $this;
    }

    public function runReport(array $configuration)
    {
        return $this->client->runReport(array_merge([
            'property' => "properties/{$this->propertyId}",
        ], $configuration));
    }

    public function batchRunReports(array $configuration)
    {
        return $this->client->batchRunReports(array_merge([
            'property' => "properties/{$this->propertyId}",
        ], $configuration));
    }
}
