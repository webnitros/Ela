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
    private int $doc_count_build = 0;
    private array $values = [];
    private array $values_build = [];

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

    public function docCountBuild()
    {
        return $this->doc_count_build;
    }

    public function setDocCountBuild(int $doc_count)
    {
        $this->doc_count_build = $doc_count;
        return $this;
    }

    public function setValues(array $aggregation)
    {
        $this->values = $this->bucket($aggregation);
        return $this;
    }

    public function setValuesBuild(array $aggregation)
    {
        $this->values_build = $this->bucket($aggregation);
        return $this;
    }

    public function bucket(array $aggregation)
    {
        $type = Map::filter($this->field());
        $result = [];
        switch ($type) {
            case 'terms':
            case 'term':
                $result = [
                    'labels' => $this->buckets($aggregation['labels']),
                    'selected' => $this->buckets($aggregation['selected']),
                ];
                break;
            case 'range':
                $result = [
                    'min' => $aggregation['min']['value'],
                    'max' => $aggregation['max']['value']
                ];
                break;
            default:
                break;
        }
        return $result;
    }

    public function values()
    {
        return $this->values;
    }

    public function valuesBuild()
    {
        return $this->values_build;
    }

    public function buckets($v)
    {
        $values = [];
        if (!empty($v['buckets'])) {
            foreach ($v['buckets'] as $bucket) {
                $values[$bucket['key']] = $bucket['doc_count'];
            }
        }
        return $values;
    }

    public function addLabel(string $name, int $doc_count)
    {
        $this->values['labels'][$name] = $doc_count;
        $this->values['selected'][$name] = $doc_count;
        $this->values_build['labels'][$name] = $doc_count;
        $this->values_build['selected'][$name] = $doc_count;
    }

    public function toArray()
    {
        return [
            'full' => [
                'doc_count' => $this->docCount(),
                'values' => $this->values(),
            ],
            'build' => [
                'doc_count' => $this->docCountBuild(),
                'values' => $this->valuesBuild(),
            ]
        ];
    }

}
