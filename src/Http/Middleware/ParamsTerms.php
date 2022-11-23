<?php
/**
 * Сортировка по баллам
 */

namespace Ela\Http\Middleware;

use Ela\Http\Controllers\Controller;
use Ela\Interfaces\Middleware;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\MultiMatch;
use Elastica\Query\Script as ScriptQuery;
use Elastica\Query\Term;
use Elastica\Script\Script;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;
use function DeepCopy\deep_copy;

class ParamsTerms implements Middleware
{

    public function handle(Controller $controller, Request $request, Event $event): void
    {
        if ($vendor = $request->get('vendor')) {
            \Ela\Facades\BoolQuery::addFilter((new Term())->setTerm('vendor', $vendor));
        }

    }
}
