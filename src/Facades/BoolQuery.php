<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 19.11.2022
 * Time: 13:26
 */

namespace Ela\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * @method static self addShould($args)
 * @method static self addMust($args)
 * @method static self addMustNot($args)
 * @method static self addFilter(\Elastica\Query\AbstractQuery $filter)
 * @method static self setBoost(float $boost)
 * @method static self setMinimumShouldMatch($minimum)
 * @method static array toArray()
 * @method static self _addQuery(string $type, $args)
 * @method static _getBaseName()
 * @method static setParam($key, $value)
 * @method static setParams(array $params)
 * @method static addParam($key, $value, string $subKey = NULL)
 * @method static getParam($key)
 * @method static hasParam($key)
 * @method static getParams()
 * @method static count()
 * @method static _convertArrayable(array $array)
 * @method static _setRawParam($key, $value)
 *
 * @see \Elastica\Query\BoolQuery
 */
class BoolQuery extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'BoolQuery';
    }
}
