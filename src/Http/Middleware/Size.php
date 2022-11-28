<?php
/**
 * Параметры для поиска по умолчанию
 */

namespace Ela\Http\Middleware;

use Ela\Http\Controllers\Controller;
use Ela\Interfaces\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;

class Size implements Middleware
{

    /**
     * @var \Illuminate\Http\Request
     */
    private Request $request;
    private $criteria;


    public function handle(Controller $controller, Request $request, Event $event): void
    {

        $controller->query()->setSize(20);

    }

}
