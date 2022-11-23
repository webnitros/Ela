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
 * @method static \Ela\Route prefixStack(string $uri)
 * @method static void post($uri, $action = null)
 * @method static void put($uri, $action = null)
 * @method static void patch($uri, $action = null)
 * @method static void delete($uri, $action = null)
 * @method static void options($uri, $action = null)
 * @method static void any($uri, $action = null)
 * @method static void group(array $attributes, $callback)
 *
 * @see \Ela\Route
 */
class Route extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Ela\Route::class;
    }
}
