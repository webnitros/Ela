<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 25.11.2022
 * Time: 09:57
 */

namespace Ela;

use Ela\Facades\Map;

class AggregationResult
{

    private int $doc_count = 0;
    private array $values = [];

    public function __construct(string $field)
    {
        $this->field = $field;
    }

    /**
     * @return string
     */
    public function field()
    {
        return $this->field;
    }

    public function docCount()
    {
        return $this->doc_count;
    }

    public function setDocCount(int $doc_count)
    {
        $this->doc_count = $doc_count;
        return $this;
    }

    public function setValues(array $aggregation)
    {
        $this->values = $this->bucket($aggregation);
        return $this;
    }


    public function values()
    {
        return $this->values;
    }

    public function toArray()
    {
        return [
            'doc_count' => $this->docCount(),
            'values' => $this->values(),
        ];
    }

}
