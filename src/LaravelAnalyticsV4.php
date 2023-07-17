<?php

namespace MrugeshTatvasoft\LaravelAnalytics;

use Google\Analytics\Data\V1beta\DimensionHeader;
use Google\Analytics\Data\V1beta\DimensionValue;
use Google\Analytics\Data\V1beta\MetricHeader;
use Google\Analytics\Data\V1beta\MetricValue;
use Google\Analytics\Data\V1beta\Row;
use Google\Analytics\Data\V1beta\RunReportResponse;

class LaravelAnalytics
{
    protected LaravelAnalyticsClient $client;

    protected bool $shouldConvertResponseToArray = true;

    public function __construct(LaravelAnalyticsClient $client)
    {
        $this->client = $client;
    }

    /**
     * Enable\Disable unwrapping to an array. Disabling this will return original response from the Analytics data v1 api
     *
     * @return $this
     */
    public function convertResponseToArray(bool $convert = true): static
    {
        $this->shouldConvertResponseToArray = $convert;

        return $this;
    }

    /**
     * I dont like the GA return types so I'm going to convert it all to static data
     */
    public function unwrapToArray(RunReportResponse $response): array
    {
        $dimensionHeaders = collect($response->getDimensionHeaders())->map(function (DimensionHeader $header) {
            return $header->getName();
        })->toArray();

        $metricHeaders = collect($response->getMetricHeaders())->map(function (MetricHeader $header) {
            return $header->getName();
        })->toArray();

        return collect($response->getRows())->map(function (Row $row) use ($dimensionHeaders, $metricHeaders) {
            $finalData = [];

            collect($row->getDimensionValues())->map(function (DimensionValue $rowValue, $rowIndex) use (&$finalData, $dimensionHeaders) {
                $finalData['dimensions'][$dimensionHeaders[$rowIndex]] = $rowValue->getValue();
            });

            collect($row->getMetricValues())->each(function (MetricValue $metricValue, $rowIndex) use (&$finalData, $metricHeaders) {
                $finalData['metrics'][$metricHeaders[$rowIndex]] = $metricValue->getValue();
            });

            return $finalData;
        })->toArray();
    }

    /**
     * Runs the report with the given configuration
     */
    public function runReport(RunReportConfiguration $configuration): RunReportResponse|array
    {
        if (! $this->shouldConvertResponseToArray) {
            return $this->client->runReport($configuration->toGoogleObject());
        }

        return $this->unwrapToArray($this->client->runReport($configuration->toGoogleObject()));
    }

    /**
     * Runs the report with the given configuration
     */
    public function batchRunReports($configuration)
    {
        if (! $this->shouldConvertResponseToArray) {
            return $this->client->batchRunReports(['requests'=>$this->prepareRequest($configuration)]);
        }

        return $this->prepareResponse($this->client->batchRunReports(['requests'=>$this->prepareRequest($configuration)]));
    }

    public function prepareRequest($request)
    {
        $requests = [];
        foreach ($request as $key => $value) {
            $requests[] = $value->getRunReportRequest($value->toGoogleObject());
        }
        return $requests;
    }

    public function prepareResponse($response)
    {
        $responses = [];

        foreach ($response->getReports() as $key => $value) {
            $responses[] = $this->getAllRows($value);
        }
        return $responses;
    }


    public function getAllRows(RunReportResponse $response)
    {
        return collect($response->getRows())->map(function (Row $row) {
            $finalData = [];

            collect($row->getDimensionValues())->map(function (DimensionValue $rowValue, $rowIndex) use (&$finalData) {
                $finalData[] = $rowValue->getValue();
            });

            collect($row->getMetricValues())->each(function (MetricValue $metricValue, $rowIndex) use (&$finalData) {
                $finalData[] = $metricValue->getValue();
            });

            return $finalData;
        })->toArray();

    }


}
