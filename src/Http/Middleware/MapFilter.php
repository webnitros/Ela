<?php

namespace Ela\Http\Middleware;

use AppM\Http\Controllers\Controller;
use AppM\Interfaces\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;

class MapFilter implements Middleware
{

    public function handle(Controller $controller, Request $request, Event $event): void
    {
        //  Загрука полей с которыми предстоит работать
        \Ela\Facades\Map::create(getenv('ES_FIELDS_PATH'), $request);
    }

}
