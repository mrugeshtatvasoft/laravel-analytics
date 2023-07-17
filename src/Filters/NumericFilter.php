<?php

namespace MrugeshTatvasoft\LaravelAnalytics\Filters;

use Google\Analytics\Data\V1beta\Filter;
use Google\Analytics\Data\V1beta\Filter\BetweenFilter;
use Google\Analytics\Data\V1beta\Filter\NumericFilter\Operation;
use Google\Analytics\Data\V1beta\NumericValue;

class NumericFilter extends AnalyticsFilter
{
    protected string $operation = 'EQUAL';

    protected string $operationCode = 'EQUAL';

    protected mixed $expression;

    protected mixed $fromNumber;

    public bool $notExpression = false;

    protected mixed $toNumber;

    public function equal($number): static
    {
        $this->operation = 'EQUAL';
        $this->operationCode = Operation::EQUAL;
        $this->expression = $number;

        return $this;
    }

    public function lessThan($number): static
    {
        $this->operation = 'LESS_THAN';
        $this->operationCode = Operation::LESS_THAN;
        $this->expression = $number;

        return $this;
    }

    public function lessThanOrEqual($number): static
    {
        $this->operation = 'LESS_THAN_OR_EQUAL';
        $this->operationCode = Operation::LESS_THAN_OR_EQUAL;
        $this->expression = $number;

        return $this;
    }

    public function greaterThan($number): static
    {
        $this->operation = 'GREATER_THAN';
        $this->operationCode = Operation::GREATER_THAN;
        $this->expression = $number;

        return $this;
    }

    public function greaterThanOrEqual($number): static
    {
        $this->operation = 'GREATER_THAN_OR_EQUAL';
        $this->operationCode = Operation::GREATER_THAN_OR_EQUAL;
        $this->expression = $number;

        return $this;
    }

    public function between($from, $to): static
    {
        $this->operation = 'between';
        $this->fromNumber = $from;
        $this->toNumber = $to;

        return $this;
    }

    private function getUnderlyingValue($number): array
    {
        if (is_string($number)) {
            return [
                'int64_value' => $number,
            ];
        }

        return [
            'double_value' => $number,
        ];
    }

    public function toArray(): array
    {
        if ($this->operation === 'between') {
            return [
                'fromValue' => $this->getUnderlyingValue($this->fromNumber),
                'toValue' => $this->getUnderlyingValue($this->toNumber),
                'not_expression' => $this->notExpression,
            ];
        }

        return [
            'operation' => $this->operation,
            'value' => $this->getUnderlyingValue($this->expression),
            'not_expression' => $this->notExpression,
        ];
    }

    public function getGoogleFilterType()
    {
        $configuration = [
            'field_name' => $this->subject,
            'numeric_filter' => $this->toGoogleTypes(),
        ];

        return new Filter($configuration);
    }

    public function setNotExpression()
    {
        $this->notExpression = true;
    }

    public function toGoogleTypes()
    {
        if ($this->operation === 'between') {
            return new BetweenFilter([
                'from_value' => new NumericValue($this->getUnderlyingValue($this->fromNumber)),
                'to_value' => new NumericValue($this->getUnderlyingValue($this->toNumber)),
            ]);
        }
        $filter = new \Google\Analytics\Data\V1beta\Filter\NumericFilter();
        $filter->setOperation($this->operationCode);
        $filter->setValue(new NumericValue($this->getUnderlyingValue($this->expression)));

        return $filter;
    }
}
