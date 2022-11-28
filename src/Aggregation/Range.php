<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 24.11.2022
 * Time: 09:20
 */

namespace Ela\Aggregation;

use Ela\Abstracts\Aggregation;
use Ela\Interfaces\AggregationInterface;
use Elastica\Aggregation\Max;
use Elastica\Aggregation\Min;
use Elastica\Param;

class Range extends Aggregation implements AggregationInterface
{

    public function aggs(Param $Param): void
    {
        $f = $this->field();
        $min = new Min('min');


        $min->setField($f);
        $Param->addAggregation($min);

        $max = new Max('max');
        $max->setField($f);
        $Param->addAggregation($max);
    }

}
