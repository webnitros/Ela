<?php
/**
 * Параметры для поиска по умолчанию
 */

namespace Ela\Http\Middleware;

use AppM\Http\Controllers\Controller;
use AppM\Interfaces\ControllerInterface;
use AppM\Interfaces\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;

class Size implements Middleware
{

    /**
     * @var \Illuminate\Http\Request
     */
    private Request $request;
    private $criteria;


    public function handle(ControllerInterface $controller, Request $request, Event $event): void
    {

        $controller->query()->setSize(20);

    }

}
