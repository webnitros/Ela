<?php
/**
 * Сортировка по умолчанию
 */

namespace Ela\Http\Middleware;

use AppM\Interfaces\ControllerInterface;
use AppM\Interfaces\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;

class DefaultSort implements Middleware
{

    public function handle(ControllerInterface $controller, Request $request, Event $event): void
    {
        $sort = $request->get('sort', getenv('ES_SORT_BY') . ':' . getenv('ES_SORT_DIR'));
        list($sortBy, $sortDir) = explode(':', $sort);

        \Ela\Facades\Query::setSort([
            $sortBy => $sortDir,
        ]);

    }
}
