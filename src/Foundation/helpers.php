<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 06.11.2022
 * Time: 14:59
 */

use Illuminate\Support\Facades\Date;

if (!function_exists('app')) {

    /**
     * Get the available container instance.
     *
     * @param string|null $abstract
     * @param array $parameters
     * @return mixed|\Ela\Foundation\Application
     */
    function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return \Ela\Foundation\Application::getInstance();
        }
        return \Ela\Foundation\Application::getInstance()->make($abstract, $parameters);
    }
}

if (!function_exists('now')) {
    /**
     * Create a new Carbon instance for the current time.
     *
     * @param \DateTimeZone|string|null $tz
     * @return \Illuminate\Support\Carbon
     */
    function now($tz = null)
    {
        return Date::now($tz);
    }
}


if (!function_exists('redirect')) {
    /**
     * @param $to
     * @return mixed
     */
    function redirect($to)
    {
        return $to;
    }
}
