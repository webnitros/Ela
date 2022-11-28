<?php
/**
 * Параметры для поиска по умолчанию
 */

namespace Ela\Http\Middleware;

use AppM\Interfaces\ControllerInterface;
use AppM\Interfaces\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;

class Source implements Middleware
{
    /**
     * @var \Illuminate\Http\Request
     */
    private Request $request;
    private $criteria;


    public function handle(ControllerInterface $controller, Request $request, Event $event): void
    {
        $controller->query()->setSource(['*']);
        #  $controller->query()->setSource(['shop_availability']);

    }

}
