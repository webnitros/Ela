<?php
/**
 * Сортировка по баллам
 */

namespace Ela\Http\Middleware;

use Ela\Facades\BoolQuery;
use Ela\Http\Controllers\Controller;
use Ela\Traintes\TermsPostTrait;
use Ela\Interfaces\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;

class PostFilter implements Middleware
{
    use TermsPostTrait;

    public function handle(Controller $controller, Request $request, Event $event): void
    {
        $this->request = $request;

        // Terms doc_count
        $this
            ->addTerms('vendor')
            ->addTerms('shop_availability')
            ->addTerms('armature_color')
            ->addTerms('armature_material')
            ->addTerms('diffuser')
            ->addTerms('dopolnitelno')
            ->addTerms('forma')
            ->addTerms('forma_plafona')
            ->addTerms('interer')
            ->addTerms('ip_class')
            ->addTerms('krepej')
            ->addTerms('lamp_socket')
            ->addTerms('lamp_style')
            ->addTerms('lamp_type')
            ->addTerms('collection')
            ->addTerms('light_temperatures')
            ->addTerms('mesto_montaza')
            ->addTerms('mesto_prim')
            ->addTerms('osobennost')
            ->addTerms('ottenok')
            ->addTerms('plafond_color')
            ->addTerms('plafond_material')
            ->addTerms('pu_dimmer')
            ->addTerms('shade_direction')
            ->addTerms('tags')
            ->addTerms('tip_podklyucheniya_new')
            ->addTerms('tip_poverhnosti_plafonov_new')
            ->addTerms('tip_upravleniya')
            ->addTerms('colors')
            ->addTerms('materials')
            ->addTerms('forms');



        // Term
        $this
            ->addTerms('country_orig')
            ->addTerms('tip_poverhnosti_plafonov');


        // Term
        $this
            ->addRange('price');


        #$Bool = $controller->BoolQuery();

        #$controller->query()->setPostFilter($Bool);


    }


}
