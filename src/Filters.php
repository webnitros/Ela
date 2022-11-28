<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 25.11.2022
 * Time: 09:57
 */

namespace Ela;

use Ela\Facades\Map;

class Filters
{
    /**
     * @var Filter[]
     */
    private array $aggs = [];

    public function add(Filter $filter)
    {
        $this->aggs[$filter->field()] = $filter;
    }

    public function get(string $key)
    {
        if (array_key_exists($key, $this->aggs)) {
            return $this->aggs[$key];
        }
        return null;
    }

    public function toArray()
    {
        $arrays = [];
        foreach ($this->aggs as $key => $tagg) {
            $arrays[$key] = $tagg->toArray();
        }
        return $arrays;
    }

}
