<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 24.11.2022
 * Time: 11:33
 */

namespace Ela\Http\Middleware\Feature;

use Ela\Http\Controllers\Controller;
use Ela\Interfaces\Middleware;
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

    }
}
