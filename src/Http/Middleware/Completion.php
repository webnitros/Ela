<?php
/**
 * Параметры для поиска по умолчанию
 */

namespace Ela\Http\Middleware;

use Ela\Http\Controllers\Controller;
use Ela\Interfaces\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;

class Completion implements Middleware
{
    public function handle(Controller $controller, Request $request, Event $event): void
    {


    }


}
