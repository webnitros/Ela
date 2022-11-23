<?php
/**
 * Сортировка по умолчанию
 */

namespace Ela\Http\Middleware;

use Ela\Http\Controllers\Controller;
use Ela\Interfaces\Middleware;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Term;
use Elastica\Query\Terms;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;

class DefaultSort implements Middleware
{

    public function handle(Controller $controller, Request $request, Event $event): void
    {
        $sort = $request->get('sort', getenv('ES_SORT_BY') . ':' . getenv('ES_SORT_DIR'));
        list($sortBy, $sortDir) = explode(':', $sort);

        \Ela\Facades\Query::setSort([
            $sortBy => $sortDir,
        ]);

    }
}
