<?php
/**
 * Параметры для поиска по умолчанию
 */

namespace Ela\Http\Middleware;

use AppM\Http\Controllers\Controller;
use AppM\Interfaces\Middleware;
use Ela\Traintes\TermsBoolTrait;
use Elastica\Query\Term;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;

class BoolQueryFilter implements Middleware
{
    use TermsBoolTrait;

    public function handle(Controller $controller, Request $request, Event $event): void
    {

        $value = $request->boolean('published', true);
        \Ela\Facades\BoolQuery::addFilter((new Term())->setTerm('published', $value));

        $this
            ->setRequest($request)
            ->addTerms('parent')
            ->addIds('ids');

        $this->marker($request);
    }

    public function marker(Request $request): void
    {
        $marker = $request->get('marker', 'availability,in_order');
    }

}
