<?php

namespace MrugeshTatvasoft\LaravelAnalytics\Filters;

use Google\Analytics\Data\V1beta\Filter;
use Google\Analytics\Data\V1beta\Filter\StringFilter\MatchType;

class StringFilter extends AnalyticsFilter
{
    public string $method = 'EXACT';

    public string $expression = '';

    public bool $isCaseSensitive = false;

    public bool $notExpression = false;

    public array $list = [];

    public bool $listFilter = false;

    public function caseSensitive(bool $caseSensitive = true): static
    {
        $this->isCaseSensitive = $caseSensitive;

        return $this;
    }

    public function exactlyMatches(string $value): static
    {
        $this->method = 'EXACT';
        $this->expression = $value;

        return $this;
    }

    public function setListValue(array $value): static
    {
        $this->list = $value;
        $this->setListFilter();
        return $this;
    }

    public function beginsWith(string $value): static
    {
        $this->method = 'BEGINS_WITH';
        $this->expression = $value;

        return $this;
    }

    public function endsWith(string $value): static
    {
        $this->method = 'ENDS_WITH';
        $this->expression = $value;

        return $this;
    }

    public function contains(string $value): static
    {
        $this->method = 'CONTAINS';
        $this->expression = $value;

        return $this;
    }

    public function full_regex(string $regexExpression): static
    {
        $this->method = 'FULL_REGEXP';
        $this->expression = $regexExpression;

        return $this;
    }

    public function partial_regex(string $regexExpression): static
    {
        $this->method = 'PARTIAL_REGEXP';
        $this->expression = $regexExpression;

        return $this;
    }

    public function toGoogleTypes()
    {
        if($this->listFilter) {
            $nativeStringFilter = new \Google\Analytics\Data\V1beta\Filter\InListFilter();
            $nativeStringFilter->setValues($this->list);
        } else {
            $nativeStringFilter = new \Google\Analytics\Data\V1beta\Filter\StringFilter();
            $nativeStringFilter->setMatchType(MatchType::value($this->method));
            $nativeStringFilter->setValue($this->expression);
        }

        $nativeStringFilter->setCaseSensitive($this->isCaseSensitive);

        return $nativeStringFilter;
    }

    public function getGoogleFilterType()
    {
        $configuration = [
            'field_name' => $this->subject,
            ($this->listFilter) ? 'in_list_filter' : 'string_filter' => $this->toGoogleTypes(),
        ];

        return new Filter($configuration);
    }

    public function setNotExpression()
    {
        $this->notExpression = true;
    }

    public function setListFilter()
    {
        $this->listFilter = true;
    }

    public function toArray(): array
    {
        return [
            'match_type' => $this->method,
            'value' => $this->expression,
            'caseSensitive' => $this->isCaseSensitive,
            'not_expression' => $this->notExpression,
        ];
    }
}
