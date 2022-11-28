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
 * @method static \Elastica\Index createIndex()
 * @method static \Elastica\Index createIndexAddDocuemnts()
 * @method static \Elastica\Response flush()
 * @method static array settings()
 * @method static array analysis()
 * @method static array mappings()
 *
 * @see \Ela\IndexBuilder
 */
class IndexBuilder extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'IndexBuilder';
    }
}
