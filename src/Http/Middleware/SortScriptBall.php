<?php
/**
 * Сортировка по баллам
 */

namespace Ela\Http\Middleware;

use AppM\Http\Controllers\Controller;
use AppM\Interfaces\Middleware;
use Elastica\Query;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;

class SortScriptBall implements Middleware
{

    public function handle(Controller $controller, Request $request, Event $event): void
    {

        /* @var Query $Query
         * $Query = app('Query');
         * $string = '_score * 2.0';
         * $params = [
         * 'param1' => 'one',
         * 'param2' => 1,
         * ];
         * $lang = 'mvel';
         * $script = new Script($string, $params, $lang);
         *
         * $query = new ScriptQuery();
         * $query->setScript($script);
         *
         * $Query->setScriptFields();
         *
         *
         * $array = $query->toArray();*/

    }
}
