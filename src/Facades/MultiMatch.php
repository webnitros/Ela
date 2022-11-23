<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 19.11.2022
 * Time: 13:54
 */

namespace Ela\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * @method static self setQuery(string $query = '')
 * @method static self setFields(array $fields = array())
 * @method static self setUseDisMax(bool $useDisMax = true)
 * @method static self setTieBreaker(float $tieBreaker = 0.0)
 * @method static self setOperator(string $operator = \Elastica\Query\MultiMatch::OPERATOR_OR)
 * @method static self setMinimumShouldMatch($minimumShouldMatch)
 * @method static self setZeroTermsQuery(string $zeroTermQuery = \Elastica\Query\MultiMatch::ZERO_TERM_NONE)
 * @method static self setCutoffFrequency(float $cutoffFrequency)
 * @method static self setType(string $type)
 * @method static self setFuzziness($fuzziness)
 * @method static self setPrefixLength(int $prefixLength)
 * @method static self setMaxExpansions(int $maxExpansions)
 * @method static self setAnalyzer(string $analyzer)
 * @method static _getBaseName()
 * @method static toArray()
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
 * @see \Elastica\Query\MultiMatch
 */
class MultiMatch extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'MultiMatch';
    }
}
