<?php

namespace Ela\Http\Middleware\Aggregation;

use Ela\Http\Controllers\Controller;
use Ela\Facades\Map;
use Ela\Facades\MultiSearch;

use Ela\Interfaces\Middleware;
use Ela\Traintes\AggregationTrait;
use Ela\Traintes\ResultsTrait;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;
use Symfony\Component\HttpKernel\KernelEvents;

class OutOfStock implements Middleware
{
    use AggregationTrait;
    use ResultsTrait;

    public function handle(Controller $controller, Request $request, Event $event): void
    {

        /**
         * Вернет количество товаров которые не в наличии на основных складах
         */
        #$modx = modY::getInstance('modY');
        #$storageGlobal = $modx->storage()->globalIds();
        $storageGlobal = [
            12,
            59,
            88,
            271
        ];

        $Query = new Query();
        $Bool = new BoolQuery();


        $params = \Ela\Facades\BoolQuery::toArray();
        $Bool->setParams($params['bool']);

        #$BoolQuery->setParams($params);


        $Terms = (new Query\Terms('shop_availability'))->setTerms($storageGlobal);


        $Bool->addMustNot($Terms);
        $Query->setPostFilter($Bool);


        $controller->newSearch('out_of_stock', $Query);


        /**
         * На выходе ловим событие отдачи ответа и добавляем в него результаты работы
         */
        /*    app('dispatcher')->addListener(KernelEvents::RESPONSE, function (\Symfony\Component\HttpKernel\Event\ResponseEvent $event) {

                $Response = $event->getResponse();
                $arrays = $Response->getOriginalContent();
                if ($Search = MultiSearch::get('out_of_stock')) {
                    $Result = Map::aggResult('marker');
                    #$Result->addLabel('out_of_stock', $Search->getTotalHits());
                    $Products = MultiSearch::get('products');
                    $Marker = $Products->getAggregation('marker');
                    #'aggs' => $this->aggregations($Products, true),
                    $aggs = [
                        'full' => $this->aggregations($Products, false),
                        'build' => $this->aggregations($Products, true),
                    ];
                    echo '<pre>';
                    print_r($aggs);
                    die;
                    $Response->setContent($arrays);
                    $event->setResponse($Response);
                }
            });*/


    }

}
