<?php

namespace Mehradsadeghi\FilterQueryString\Filters\ComparisonClauses;

use Mehradsadeghi\FilterQueryString\FilterContract;

class GreaterOrEqualTo extends BaseComparison implements FilterContract
{
    public $operator = '>=';
}
