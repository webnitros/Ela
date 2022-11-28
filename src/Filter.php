<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 25.11.2022
 * Time: 09:57
 */

namespace Ela;

use Ela\Facades\Map;

class Filter extends AggregationResult
{
    protected array $values = [];

    public function buildValues()
    {

        try {
            $field = $this->field();
            $filters = \Ela\Facades\MultiSearch::get('filters');
            if ($aggregation = $filters->getAggregation($field)) {
                $this->bucket('addDocCountDefault', $aggregation);
            }

            $products = \Ela\Facades\MultiSearch::get('products');
            if ($aggregation = $products->getAggregation($field)) {
                $this->bucket('addDocCount', $aggregation);
            }
            return true;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function create(string $key, $values = null)
    {
        if (!array_key_exists($key, $this->values)) {
            $this->values[$key] = [
                'default_doc_count' => 0,
                'doc_count' => 0,
            ];
        }

        if ($values) {
            $this->values[$key]['values'] = $values;
        }
    }

    public function addDocCountDefault(string $key, int $doc_count)
    {
        $this->create($key);
        $this->values[$key]['default_doc_count'] = $doc_count;
        return $this;
    }

    public function addDocCount(string $key, int $doc_count)
    {
        $this->create($key);
        $this->values[$key]['doc_count'] = $doc_count;
        return $this;
    }


    public function bucket($method, array $aggregation)
    {
        $type = Map::filter($this->field());
        $result = [];
        switch ($type) {
            case 'terms':
                $this->buckets($method, $aggregation['labels'], $aggregation['selected']);
                break;
            case 'term':
                $this->buckets($method, $aggregation['labels'], $aggregation['selected'], true);
                break;
            case 'range':

                $this->create($this->field(), [
                    'min' => $aggregation['min']['value'],
                    'max' => $aggregation['max']['value']
                ]);


                if ($method === 'addDocCount') {
                    $this->values[$this->field()]['doc_count'] = $aggregation['doc_count'];
                }

                if ($method === 'addDocCountDefault') {
                    $this->values[$this->field()]['default_doc_count'] = $aggregation['doc_count'];
                }


                break;
            default:
                break;
        }
        return $result;
    }

    public function buckets($method, $labels, $selected, $term = false)
    {
        if (!empty($labels['buckets'])) {
            foreach ($labels['buckets'] as $bucket) {
                $this->{$method}($bucket['key'], $bucket['doc_count']);
            }
        }
        if (!empty($selected['buckets'])) {
            foreach ($selected['buckets'] as $bucket) {
                $this->{$method}($bucket['key'], $bucket['doc_count']);
            }
        }

    }


    public function toArray()
    {
        return $this->values;
    }

}
