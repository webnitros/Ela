<?php

namespace Ela\Http\Controllers\Search;

use Ela\Facades\MultiSearch;
use Ela\Http\Controllers\Controller;
use Ela\Http\Middleware\BoolQueryFilter;
use Ela\Http\Middleware\Feature\Marker;
use Ela\Http\Middleware\Feature\ShopAvailability;
use Ela\Http\Middleware\MapFilter;
use Ela\Http\Middleware\PostFilter;
use Ela\Http\Middleware\QueryString;
use Ela\Http\Middleware\QueryStringHighLight;
use Ela\Http\Middleware\Results;
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
        Marker::class, // Фильтры по умолчанию
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
        Results::class // Записываем результаты поиска
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

        $Query->setQuery($this->BoolQuery());


        $Post = $this->PostFilter();
        if (is_array($Post->getParams())) {
            $Query = $Query->setPostFilter($this->PostFilter());
        }

        $this->newSearch('products', $Query);

        ########################
        ########################
        ########################

        // ВЫПОЛНЯЕМ ЗАПРОС НА СЕРВЕР
        MultiSearch::create($this->createSearch()->search());


        // Выполняем запрос
        $params = $Query->toArray();
        try {
            $Products = MultiSearch::get('products');
            return new Response([
                'total' => $Products->getTotalHits(),
                'results' => $this->products(),
                'params' => $params,
            ]);
        } catch (\Exception $e) {
            return new Response([
                'params' => $params,
                'results' => [
                    'error' => $e->getMessage()
                ]
            ]);
        }


    }

}
