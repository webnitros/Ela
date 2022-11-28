<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 19.11.2022
 * Time: 13:26
 */

namespace Ela\Facades;


use Ela\Abstracts\Aggregation;
use Ela\Interfaces\AggregationInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Ela\MultiSearch create(\Elastica\Multi\ResultSet $ResultSet)
 * @method static \Elastica\ResultSet get(string $key)
 * @method static bool has(string $key)
 *
 * @see \Ela\MultiSearch
 */
class MultiSearch extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'elastic.multi_search';
    }
}
