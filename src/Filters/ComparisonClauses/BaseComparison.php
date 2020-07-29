<?php

namespace Mehradsadeghi\FilterQueryString\Filters\ComparisonClauses;

use InvalidArgumentException;
use Mehradsadeghi\FilterQueryString\Filters\BaseClause;

abstract class BaseComparison extends BaseClause
{
    protected $isDateTime = false;
    protected $method;
    protected $normalized = [];

    public function __construct($query, $filter, $values)
    {
        parent::__construct($query, $filter, $values);

        $this->normalizeValues($values);
    }

    public function apply()
    {
        foreach ($this->normalized as $field => $value) {
            $this->query->{$this->determineMethod($value)}($field, $this->operator, $value);
        }
    }

    protected function determineMethod($value)
    {
        return isDateTime($value) ? 'whereDate' : 'where';
    }

    protected function normalizeValues($values)
    {
        foreach ((array)$values as $value) {

            if (!hasComma($value)) {
                throw new InvalidArgumentException('comparison values should be comma separated.');
            }

            [$field, $val] = separateCommaValues($value);

            $this->normalized[$field] = $val;
        }
    }
}
