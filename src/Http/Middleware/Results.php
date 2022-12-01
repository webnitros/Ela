<?php
/**
 * Параметры для поиска по умолчанию
 */

namespace Ela\Http\Middleware;

use Ela\Http\Controllers\Controller;
use Ela\Interfaces\Middleware;
use Ela\Facades\Map;
use Ela\Facades\MultiSearch;
use Ela\Filter;
use Ela\Filters;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;
use Symfony\Component\HttpKernel\KernelEvents;

class Results implements Middleware
{

    public function handle(Controller $controller, Request $request, Event $event): void
    {
        /**
         * На выходе ловим событие отдачи ответа и добавляем в него результаты работы
         */
        app('dispatcher')->addListener(KernelEvents::RESPONSE, function (\Symfony\Component\HttpKernel\Event\ResponseEvent $event) {

            $Request = $event->getRequest();

            // Только если это не автокомплите
            if (!$Request->get('autocomplite')) {

                /* @var \Illuminate\Http\Response $Response */
                $Response = $event->getResponse();
                $arrays = $Response->getOriginalContent();


                $Fileds = Map::fieldsAggregation();

                $Filters = new Filters();
                foreach ($Fileds as $filed) {
                    $Filter = new Filter($filed);
                    if ($Filter->buildValues()) {
                        $Filters->add($Filter);
                    }
                }


                if ($Search = MultiSearch::get('out_of_stock')) {
                    if ($Marker = $Filters->get('marker')) {
                        $total = $Search->getTotalHits();
                        $Marker->addDocCountDefault('out_of_stock', $total);
                        $Marker->addDocCount('out_of_stock', $total);
                    }
                }

                $arrays['aggregations'] = $Filters->toArray();

                $arrays['suggest'] = [];
                $arrays['completion'] = [];

                if ($suggest = MultiSearch::get('suggest')) {
                    if ($suggest->hasSuggests()) {
                        $arrays['suggest'] = \Ela\Handle\Suggest::create($suggest);
                    }
                }

                if ($completion = MultiSearch::get('completion')) {
                    if ($completion->hasSuggests()) {
                        $arrays['completion'] = \Ela\Handle\Completion::create($completion);
                    }
                }

                $Response->setContent($arrays);
                $event->setResponse($Response);
            }


        });
    }


}
