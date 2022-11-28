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
 * @see \Ela\Index
 */
class Index extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'Index';
    }
}
