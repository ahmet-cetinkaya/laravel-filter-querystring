<?php

namespace Mehradsadeghi\FilterQueryString\Filters;

use InvalidArgumentException;

abstract class BaseClause {

    public $query;
    public $filter;
    public $values;

    public function __construct($query, $filter, $values)
    {
        $this->query = $query;
        $this->filter = $filter;
        $this->values = $values;
    }

    protected function separateCommaValues($value)
    {
        return explode(',', $value);
    }

    protected function hasComma($value)
    {
        return strpos($value, ',');
    }

    protected function validate($message = null)
    {
        if (is_null($this->values)) {
            throw new InvalidArgumentException($message);
        }
    }
}
