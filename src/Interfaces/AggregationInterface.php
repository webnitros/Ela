<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 24.11.2022
 * Time: 10:39
 */

namespace Ela\Interfaces;

use Elastica\Param;

interface AggregationInterface
{
    public function aggs(Param $Param): void;
}
