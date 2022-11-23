<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 19.11.2022
 * Time: 09:41
 */

namespace Ela\Facades;

use Illuminate\Support\Facades\Facade;

/**
 *
 * @method static __construct($query = NULL)
 * @method static self create($query)
 * @method static self setRawQuery(array $query)
 * @method static self setQuery(\Elastica\Query\AbstractQuery $query)
 * @method static getQuery()
 * @method static self setFrom(int $from)
 * @method static self setSort(array $sortArgs)
 * @method static self addSort($sort)
 * @method static self setTrackScores(bool $trackScores = true)
 * @method static self setHighlight(array $highlightArgs)
 * @method static self addHighlight($highlight)
 * @method static self setSize(int $size = 10)
 * @method static self setExplain($explain = true)
 * @method static self setVersion($version = true)
 * @method static self setStoredFields(array $fields)
 * @method static self setFieldDataFields(array $fieldDataFields)
 * @method static self setScriptFields($scriptFields)
 * @method static self addScriptField(string $name, \Elastica\Script\AbstractScript $script)
 * @method static self addAggregation(\Elastica\Aggregation\AbstractAggregation $agg)
 * @method static array toArray()
 * @method static self setMinScore(float $minScore)
 * @method static self setSuggest(\Elastica\Suggest $suggest)
 * @method static self setRescore($rescore)
 * @method static self setSource($params)
 * @method static self setPostFilter(\Elastica\Query\AbstractQuery $filter)
 * @method static self setCollapse(\Elastica\Collapse $collapse)
 * @method static self setPointInTime(\Elastica\PointInTime $pit)
 * @method static self setIndicesBoost(array $indicesBoost)
 * @method static self setTrackTotalHits($trackTotalHits = true)
 * @method static setParam($key, $value)
 * @method static setParams(array $params)
 * @method static addParam($key, $value, string $subKey = NULL)
 * @method static getParam($key)
 * @method static hasParam($key)
 * @method static getParams()
 * @method static count()
 * @method static _convertArrayable(array $array)
 * @method static _getBaseName()
 * @method static _setRawParam($key, $value)
 * @see \Elastica\Query
 */
class Query extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Elastica\Query::class;
    }
}
