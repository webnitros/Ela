<?php

namespace Ela\Http\Middleware;

use Ela\Http\Controllers\Controller;
use Ela\Interfaces\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;

class MapFilter implements Middleware
{
    public function handle(Controller $controller, Request $request, Event $event): void
    {
        //  Загрука полей с которыми предстоит работать
        $path = getenv('ES_SETTINS_PATH') . 'fields.yaml';
        if (!file_exists($path)) {
            throw new \Exception('no file fields ' . $path);
        }
        $Map = \Ela\Facades\Map::create($path);
        $Map->setRequest($request);
    }

}
