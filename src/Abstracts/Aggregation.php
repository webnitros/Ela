<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 24.11.2022
 * Time: 10:37
 */

namespace Ela\Abstracts;


use Ela\Facades\Query;
use Elastica\Aggregation\BucketScript;
use Elastica\Aggregation\Filter;
use Elastica\Aggregation\Filters;
use Elastica\Aggregation\Stats;
use Elastica\Aggregation\Terms;
use Elastica\Aggregation\ValueCount;
use Elastica\Query\BoolQuery;
use Illuminate\Http\Request;

abstract class Aggregation
{

    protected string $field;

    /* @var \Elastica\Aggregation\Filter|null $Filter */
    protected $Filter;
    private string $name;

    public function __construct(string $field)
    {
        $this->field = $field;
    }

    public function field()
    {
        return $this->field;
    }

    public function name()
    {
        return $this->name;
    }

    public function bool(BoolQuery $BoolQuery)
    {
        $field = $this->field();
        $arrays = $BoolQuery->toArray();
        $filter = [];
        $unset = false;
        if (!empty($arrays['bool'])) {

            if (gettype($arrays['bool']) === 'array') {
                if (!empty($arrays['bool']['filter'])) {
                    $filter = $arrays['bool']['filter'];
                    foreach ($filter as $k => $item) {
                        if (!empty($item['terms'])) {
                            $value = $item['terms'];
                        } else if ($item['term']) {
                            $value = $item['term'];
                        } else if ($item['range']) {
                            $value = $item['range'];
                        }
                        if (!empty($value[$field])) {
                            unset($filter[$k]);
                            $unset = true;
                        }
                    }
                }
            }
        }
        if ($unset) {
            $filter = array_values($filter);
        }

        $newBoolQuery = new BoolQuery();
        $newBoolQuery->setParams([
            'must' => $filter
        ]);
        return $newBoolQuery;
    }


    /**
     * @param \Elastica\Query\BoolQuery $BoolQuery
     * @return $this
     */
    public function filter(BoolQuery $BoolQuery, $name = null)
    {
        $this->name = $name ?? $this->field();
        $this->Filter = new Filter($this->name());
        $this->Filter->setFilter($this->bool($BoolQuery));
        return $this;
    }


    /**
     * @param \Elastica\Query $Query
     * @param null $name - имя для агрегации
     * @return $this
     */
    public function add(\Elastica\Query $Query, $name = null)
    {
        if (!$Filter = $this->Filter) {
            $this->name = $name ?? $this->field();
            $Filter = new Filter($this->name());
            $Filter->setFilter((new BoolQuery())->setParams(['must' => []]));
        }

        // Если фильтры отсутствуют то накладываем только запрос
        $this->aggs($Filter);
        $Query->addAggregation($Filter);
        return $this;
    }

}
