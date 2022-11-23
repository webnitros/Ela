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

class DefaultFilter implements Middleware
{
    public function handle(Controller $controller, Request $request, Event $event): void
    {
        /**
         * Показываем только товары которые опубликованные
         */

        if ($published = $request->boolean('published', true)) {
            #\Ela\Facades\BoolQuery::addFilter((new Term())->setTerm('published', $published));
        }

        /**
         * Только товары в наличии
         * - нужно чтобы можно было фильтровать остатки по магазинам
         */
        if ($availability = $request->boolean('availability', true)) {
            #\Ela\Facades\BoolQuery::addFilter((new Term())->setTerm('availability', $availability));
        }

        /**
         * Записиываем список складов на которых храняться остатки
         */
        if ($request->get('shop_availability')) {
            $controller->validatorResponse($request, [
                'shop_availability' => ['array']
            ]);
            if ($shop_availability = $request->get('shop_availability')) {
                $Temrs = new Terms('shop_availability', $shop_availability);
                \Ela\Facades\BoolQuery::addFilter($Temrs);
            }
        }

    }
}
