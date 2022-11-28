<?php

namespace Ela\Http\Controllers\Search;

use Ela\Facades\MultiSearch;
use Ela\Http\Controllers\Controller;
use Ela\Http\Middleware\BoolQueryFilter;
use Ela\Http\Middleware\Feature\ShopAvailability;
use Ela\Http\Middleware\MapFilter;
use Ela\Http\Middleware\PostFilter;
use Ela\Http\Middleware\QueryString;
use Ela\Http\Middleware\QueryStringHighLight;
use Ela\Http\Middleware\Size;
use Ela\Http\Middleware\SortScriptBall;
use Ela\Http\Middleware\Aggregation;
use Ela\Http\Middleware\Source;
use Ela\Traintes\ResultsTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QueryController extends Controller
{
    use ResultsTrait;

    protected $middleware = [
        #DefaultSort::class, // Сортировка по умолчанию
        Size::class, // limit size
        Source::class, // limit size
        MapFilter::class, // Поля для фильтрации
        SortScriptBall::class, // Сортировка по баллам

        // Получаем все фильтры
        BoolQueryFilter::class, // Фильтры по умолчанию
        PostFilter::class, // Наложение фильтров

        // Затем наложение фильтров происхоит

        QueryString::class, // Морфолигический поиск
        QueryStringHighLight::class, // Подстветка синтаксиса
        // --------------


        // Feature - дополнительные фильтры для кастомного наложения
        //OutOfStock::class, // Отсутствует в продаже
        ShopAvailability::class, // Наложение фильтров склады

        // Сперва считаем агрегации для построения фильтров
        // Aggregation
        Aggregation\Counting::class, // при построении фильтро POST фильтры не должны накладывать
        Aggregation\CurrentPost::class, // Текущие агре
        Aggregation\OutOfStock::class, // при построении фильтро POST фильтры не должны накладывать
        // --------------
    ];

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request)
    {
        ########################
        ##### ВТОРОЙ ЗАПРОС С ТОВАРАМИ
        ########################

        $Query = $this->query();

        $Query = $Query->setQuery($this->BoolQuery());
        $Query = $Query->setPostFilter($this->PostFilter());

        $this->newSearch('products', $Query);

        ########################
        ########################
        ########################

        // ВЫПОЛНЯЕМ ЗАПРОС НА СЕРВЕР
        MultiSearch::create($this->createSearch()->search());

        $params = $Query->toArray();
        try {

            // Записываем результаты всех фильтров
            if ($Aggregation = MultiSearch::get('filters')) {
                $this->aggregations($Aggregation, false);
                /*   $building_filters = [
                       'query' => $Aggregation->getQuery()->toArray()
                   ];*/
            }


            $Products = MultiSearch::get('products');
            $data = [
                'total' => $Products->getTotalHits(),
                'results' => $this->products(),
                'params' => $params,
                'suggest' => $Products->getSuggests(),
                'aggs' => $this->aggregations($Products, true),
            ];


        } catch (\Exception $e) {
            return new Response([
                'params' => $params,
                'results' => [
                    'error' => $e->getMessage()
                ]
            ]);
        }

        return new Response($data);

    }

}
