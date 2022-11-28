<?php
/**
 * Агтегации с наложение POST фильтров
 */

namespace Ela\Http\Middleware\Aggregation;

use AppM\Interfaces\ControllerInterface;
use Ela\Facades\Map;
use AppM\Http\Controllers\Controller;
use AppM\Interfaces\Middleware;
use Ela\Traintes\AggregationTrait;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;

class CurrentPost implements Middleware
{
    use AggregationTrait;

    public function handle(ControllerInterface $controller, Request $request, Event $event): void
    {
        // Далее все агрегации нужно добавить в основной запрос чтобы тот вернул данны
        $Query = $controller->query();
        $Post = $controller->PostFilter();
        Map::aggregation('marker')->add($Query);
        Map::aggregation('lamp_style')->filter($Post)->add($Query);
        Map::aggregation('price')->filter($Post)->add($Query);
    }

}
