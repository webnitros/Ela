<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 19.11.2022
 * Time: 13:26
 */

namespace Ela\Facades;


use Ela\Abstracts\Aggregation;
use Ela\AggregationResult;
use Ela\Interfaces\AggregationInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Ela\Map create(string $file)
 * @method static mixed get(string $field)
 * @method static boolean isAgg(string $field)
 * @method static boolean type(string $field)
 * @method static boolean aggs(string $field)
 * @method static boolean bool(string $field)
 * @method static string filter(string $field)
 * @method static \Ela\Map setDocCount(string $field, int $doc_count)
 * @method static \Ela\Map setValues(string $field, array $values)
 * @method static int doc_count(string $field)
 * @method static array|null values(string $field)
 * @method static bool fielddata(string $field)
 * @method static \Illuminate\Http\Request request()
 * @method static \Ela\Map setRequest(\Illuminate\Http\Request $request)
 * @method static AggregationInterface|Exception aggregation(string $field)
 * @method static AggregationResult aggResult(string $field)
 * @method static array fieldsAggregation()
 * @method static array getResults()
 * @method static array toArray()
 *
 * @see \Ela\Map
 */
class Map extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'elastic.map';
    }
}
