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
     * @var array|false|mixed|string
     */
    public $pathSetting;
    /**
     * @var array
     */
    private $map;
    /**
     * @var \Illuminate\Http\Request
     */
    private array $doc_count = [];
    private array $values = [];

    public function __construct($pathSetting = null)
    {
        $this->pathSetting = $pathSetting ?? getenv('ES_SETTINS_PATH');
    }

    public function pathSetting()
    {
        return $this->pathSetting;
    }

    public function create(string $file)
    {
        $this->map = Yaml::parseFile($file);
        return $this;
    }


    public function setMap(array $arrays)
    {
        $this->map = $arrays;
        return $this;
    }

    public function setRequest(\Illuminate\Http\Request $request)
    {
        $this->request = $request;
        return $this;
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

    public function frontend(string $field)
    {
        if ($meta = $this->get($field)) {
            return !empty($meta['frontend']);
        }
        return false;
    }

    public function deployed(string $field)
    {
        if ($meta = $this->get($field)) {
            return !empty($meta['deployed']);
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

    public function fielddata(string $field)
    {
        if ($meta = $this->get($field)) {
            return !empty($meta['fielddata']);
        }
        return false;
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
            case 'term':
                $class = \Ela\Aggregation\Terms::class;
                break;
            case 'range':
                $class = \Ela\Aggregation\Range::class;
                break;
            default:
                throw new \Exception("class '{$class}' not found. Filed '{$field}'");
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


    public function fieldsAggregation()
    {
        $arrays = [];
        foreach ($this->map as $k => $v) {
            if ($v['aggs']) {
                $arrays[] = $k;
            }
        }
        return $arrays;
    }

    public function toArray()
    {
        return $this->map;
    }
}
