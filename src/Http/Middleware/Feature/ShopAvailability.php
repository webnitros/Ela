<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 24.11.2022
 * Time: 11:33
 */

namespace Ela\Http\Middleware\Feature;


use AppM\Http\Controllers\Controller;
use AppM\Interfaces\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;

class ShopAvailability implements Middleware
{

    public function handle(Controller $controller, Request $request, Event $event): void
    {
        // Если наложили фильтр: Отсутствует в продаже
        if (!$request->boolean('out_of_stock')) {
            $this->default($controller, $request);
        }
    }

    public function default(Controller $controller, Request $request)
    {
        /**
         * Записиываем список складов на которых храняться остатки
         */
       /* if ($request->get('shop_availability')) {
            $controller->validatorResponse($request, [
                'shop_availability' => ['array']
            ]);
            if ($shop_availability = $request->get('shop_availability')) {
                $Temrs = new Terms('shop_availability', $shop_availability);
                \Ela\Facades\BoolQuery::addFilter($Temrs);
            }
        }*/
    }
}
