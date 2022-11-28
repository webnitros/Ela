<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 24.11.2022
 * Time: 11:33
 */

namespace Ela\Http\Middleware\Feature;

use Ela\Http\Controllers\Controller;
use Ela\Interfaces\Middleware;
use Ela\Traintes\TermsPostTrait;
use Elastica\Query\BoolQuery;
use Elastica\Query\Terms;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;

class Marker implements Middleware
{
    use TermsPostTrait;

    public function handle(Controller $controller, Request $request, Event $event): void
    {
        $markers = [];
        if ($tmp = $request->get('marker')) {
            foreach ($tmp as $k => $key) {
                $markers[$key] = true;
            }
        }


        $this->setRequest($request);

        $availability = false;
        $out_of_stock = false;
        $in_order = false;

        $keys = [
            'sale',
            'new',
            'in_order',
            'availability',
            'out_of_stock',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                if ($request->boolean($key)) {
                    if (!array_key_exists($key, $markers)) {
                        $markers[$key] = true;
                    }
                }
            }
        }


        foreach ($markers as $key => $boolean) {
            if ($boolean) {
                switch ($key) {
                    case 'sale':
                    case 'new':
                        $this->addTerm($key);
                        break;
                    case 'in_order':
                        $in_order = true;
                        break;
                    case 'availability':
                        $availability = true;
                        break;
                    case 'out_of_stock':
                        $out_of_stock = true;
                        break;
                    default:
                        break;
                }
            }
        }


        if (!$out_of_stock && !$availability && !$in_order) {
            $availability = true;
        }

        if ($in_order) {
            $this->in_order($controller, $request);
        }

        $mode = null;
        if ($availability && !$out_of_stock) {
            $mode = 'availability';
        } else if ($availability && $out_of_stock) {
            $mode = 'availability_out_of_stock';
        } else if (!$availability && $out_of_stock) {
            $mode = 'out_of_stock';
        }


        if ($storages = $this->storages($controller, $request)) {
            switch ($mode) {
                case 'availability':
                    // в наличии
                    $this->availability($storages);
                    break;
                case 'availability_out_of_stock':
                    // в наличии и отсутствуют в продаже
                    $this->availability_out_of_stock($storages);
                    break;
                case 'out_of_stock':
                    //отсутствуют в продаже
                    $this->out_of_stock($storages);
                    break;
                default:
                    break;
            }
        }

    }


    public function availability_out_of_stock($storages)
    {
        $Terms1 = $this->out_of_stock($storages);
        $Terms2 = $this->availability($storages);

        $Bool = new BoolQuery();
        $Bool->addFilter($Terms1);
        $Bool->addMustNot($Terms2);

        \Ela\Facades\BoolQuery::addShould($Bool);

    }

    public function out_of_stock($storages)
    {
        return new Terms('shop_availability', $storages);
    }

    public function availability($storages)
    {
        return new Terms('shop_availability', $storages);
    }

    public function in_order(\AppM\Http\Controllers\Controller $controller, Request $request)
    {
        $this->addTerm('in_order');
    }


    /**
     * Возвращаем склады
     * @param \AppM\Http\Controllers\Controller $controller
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function storages(\AppM\Http\Controllers\Controller $controller, Request $request)
    {
        if (!$storage = $request->get('shop_availability')) {
            $modx = modY::getInstance('modY');
            $storage = $modx->storage()->globalIds();
        }
        return $storage;
    }


}
