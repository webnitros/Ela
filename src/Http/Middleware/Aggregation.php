<?php
/**
 * Параметры для поиска по умолчанию
 */

namespace Ela\Http\Middleware;

use Ela\Http\Controllers\Controller;
use Ela\Interfaces\Middleware;
use Elastica\Query\BoolQuery;
use Elastica\Query\Term;
use Elastica\Query\Terms;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;

class Aggregation implements Middleware
{

    public function handle(Controller $controller, Request $request, Event $event): void
    {

        #echo '<pre>';
        #print_r($request->all());
        #die;

    }
}
