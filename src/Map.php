<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 25.11.2022
 * Time: 09:57
 */

namespace Ela;


use Ela\Interfaces\AggregationInterface;
use Illuminate\Http\Request;
use Symfony\Component\Yaml\Yaml;

class Map
{
    /**
     * @var array
     */
    private $map;
    /**
     * @var \Illuminate\Http\Request
     */
    protected Request $request;
    private array $doc_count = [];
    private array $values = [];

    public function create(string $file, Request $request)
    {
        $this->map = Yaml::parseFile($file);
        $this->request = $request;

        // Поля по умолчаию для которых всегда включены агрегации
        /* $keys = [
             'marker',
             'price',
             'shop_availability',
             'lamp_style',
         ];

         // Выстроить агрегации по указанным полям
            $suggestionsFields = $request->get('suggestionsFields', null);
            if ($suggestionsFields) {
                $keysFields = explode(',', $suggestionsFields);
                $keysFields = array_map('trim', $keysFields);
                foreach ($keysFields as $key) {
                    if ($request->request->has($key)) {
                        if ($data = $request->request->get($key)) {
                            if ($data['aggs']) {
                                $keys[] = $key;
                            }
                        }
                    }
                }
            }*/

    }

    public function request()
    {
        return $this->request;
    }

    public function bool(string $field)
    {
        if ($meta = $this->get($field)) {
            return !empty($meta['bool']);
        }
        return false;
    }

    public function aggs(string $field)
    {
        if ($meta = $this->get($field)) {
            return !empty($meta['aggs']);
        }
        return false;
    }

    public function type(string $field)
    {
        if ($meta = $this->get($field)) {
            return @$meta['type'];
        }
        return null;
    }

    public function filter(string $field)
    {
        if ($meta = $this->get($field)) {
            return @$meta['filter'];
        }
        return null;
    }


    /**
     * @param string $field
     * @return AggregationInterface
     * @throws \Exception
     */
    public function aggregation(string $field)
    {
        if (!$meta = $this->get($field)) {
            throw new \Exception("field {$field} not map");
        }

        if (!array_key_exists('filter', $meta)) {
            throw new \Exception("field {$field} not found filter key");
        }

        $filter = $meta['filter'];
        $class = null;
        switch ($filter) {
            case 'terms':
                $class = \Ela\Aggregation\Terms::class;
                break;
            case 'range':
                $class = \Ela\Aggregation\Range::class;
                break;
            default:
                break;
        }

        return new $class($field);
    }

    public function get(string $field)
    {
        if (array_key_exists($field, $this->map)) {
            return $this->map[$field];
        }
        return null;
    }


    public function doc_count(string $field)
    {
        if (array_key_exists($field, $this->doc_count)) {
            return $this->doc_count[$field];
        }
        return 0;
    }

    public function setDocCount(string $field, int $doc_count)
    {
        $this->doc_count[$field] = $doc_count;
        return $this;
    }

    public function setValues(string $field, array $values)
    {
        $this->values[$field] = $values;
        return $this;
    }

    public function values(string $field)
    {
        if (array_key_exists($field, $this->values)) {
            return $this->values[$field];
        }
        return null;
    }


    /* @var \Ela\AggregationResult[] $results */
    private $results = [];

    public function aggResult(string $field)
    {
        if (array_key_exists($field, $this->results)) {
            return $this->results[$field];
        }
        $result = new AggregationResult($field);
        $this->results[$field] = $result;
        return $result;
    }

    public function getResults()
    {
        $results = [];
        /* @var \Ela\AggregationResult $result */
        foreach ($this->results as $field => $result) {
            $results[$field] = [
                'full' => [
                    'doc_count' => $result->docCount(),
                    'values' => $result->values(),
                ],
                'build' => [
                    'doc_count' => $result->docCountBuild(),
                    'values' => $result->valuesBuild(),
                ]

            ];
        }
        return $results;
    }


}