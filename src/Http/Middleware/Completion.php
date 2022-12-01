<?php
/**
 * Автокомплиты
 */

namespace Ela\Http\Middleware;

use Ela\Http\Controllers\Controller;
use Ela\Interfaces\Middleware;
use Elastica\Query;
use Elastica\Search;
use Elastica\Suggest;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;

class Completion implements Middleware
{
    public function handle(Controller $controller, Request $request, Event $event): void
    {

        ##############################
        ###### Автокомплиты
        ##############################
        if ($query = $request->get('query')) {

            $index = $controller->index()->getClient()->getIndex(getenv('ES_INDEX_COMPLITION'));

            $suggest = new \Elastica\Suggest\Completion('completion', 'word');
            $suggest->setText($query);
            $suggest->setSize(10);

            $Search = new Search($index->getClient());


            $sug = new Suggest();
            $sug->addSuggestion($suggest);
            $Search->setQuery(Query::create($sug));

            $controller->addSearch('completion', $Search, $index);

        }
    }


}
