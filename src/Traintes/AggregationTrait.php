<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 24.11.2022
 * Time: 14:13
 */

namespace Ela\Traintes;

use Ela\Facades\Map;

trait AggregationTrait
{
    public function add(string $field)
    {
        $Term = Map::aggregation($field);
        echo '<pre>';
        print_r($Term);
        die;
        $Term->build($this->criteria, $this->request);
        return $this;
    }


}
