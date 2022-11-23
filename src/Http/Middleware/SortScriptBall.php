<?php
/**
 * Сортировка по баллам
 */

namespace Ela\Http\Middleware;

use Ela\Http\Controllers\Controller;
use Ela\Interfaces\Middleware;
use Elastica\Query;
use Elastica\Query\MultiMatch;
use Elastica\Query\Script as ScriptQuery;
use Elastica\Script\Script;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;
use function DeepCopy\deep_copy;

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
