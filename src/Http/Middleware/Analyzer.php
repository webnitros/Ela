<?php
/**
 * Параметры для поиска по умолчанию
 */

namespace Ela\Http\Middleware;

use Ela\Http\Controllers\Controller;
use Ela\Interfaces\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;

class Analyzer implements Middleware
{
    public function handle(Controller $controller, Request $request, Event $event): void
    {
        $controller->validatorResponse($request, [
            'analyzer' => 'required',
            'text' => 'required',
        ]);
        $text = $request->get('text');
        $analyzer = $request->get('analyzer');

        $params = [
            'analyzer' => $analyzer,
            'text' => $text
        ];

        $returnedTokens = $controller->index()->analyze($params);
        $request->attributes->add(['data' => $returnedTokens]);
    }


}
