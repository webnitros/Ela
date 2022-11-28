<?php
/**
 * Параметры для поиска по умолчанию
 */

namespace Ela\Http\Middleware\Aggregation;

use Ela\Facades\Map;
use Ela\Traintes\AggregationTrait;
use AppM\Http\Controllers\Controller;
use AppM\Interfaces\Middleware;
use Elastica\Query;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;

class Counting implements Middleware
{
    use AggregationTrait;

    public function handle(Controller $controller, Request $request, Event $event): void
    {
        $Query = new Query();
        $Query->setQuery($controller->BoolQuery());

        // Наложение фильров
        Map::aggregation('marker')->add($Query);
        Map::aggregation('lamp_style')->add($Query);
        Map::aggregation('price')->add($Query);

        $controller->newSearch('filters', $Query);

        // Наложение фильров
        #  $Filter = Map::aggregation('price')
        #     ->filter($Bool)->add();


        /*  $this->add('lamp_style')
              ->add('armature_color')
              ->add('armature_material')
              ->add('diffuser')
              ->add('forma')
              ->add('forma_plafona')
              ->add('interer')
              ->add('krepej')
              ->add('ip_class')
              ->add('lamp_socket')
              ->add('lamp_style')
              ->add('lamp_type')
              ->add('mesto_montaza')
              ->add('mesto_prim')
              ->add('plafond_color')
              ->add('plafond_material')
              ->add('shade_direction')
              ->add('tip_poverhnosti_plafonov_new')
              ->add('shop_availability')
              ->add('colors')
              ->add('materials')
              ->add('forms')
              ->add('marker')
              ->add('shop_availability');*/


    }

}
