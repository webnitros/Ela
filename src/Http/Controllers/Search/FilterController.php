<?php

namespace Ela\Http\Controllers\Search;

use Ela\Facades\Index;
use Ela\Http\Controllers\Controller;
use Ela\Http\Middleware\Aggregation;
use Ela\Http\Middleware\DefaultFilter;
use Ela\Http\Middleware\DefaultSort;
use Ela\Http\Middleware\ParamsTerms;
use Ela\Http\Middleware\QueryString;
use Ela\Http\Middleware\QueryStringHighLight;
use Ela\Http\Middleware\SortScriptBall;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    protected $middleware = [
        #DefaultSort::class, // Сортировка по умолчанию
        DefaultFilter::class, // Фильтры по умолчанию
        ParamsTerms::class, // Наложение фильтров
        QueryString::class, // Морфолигический поиск
        QueryStringHighLight::class, // Подстветка синтаксиса
        SortScriptBall::class, // Сортировка по баллам
        Aggregation::class, // Агрегации
    ];

    /**
     * Автоматически сброс пароля и отправка на email
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function get(Request $request)
    {

        $index = Index::createIndex();
        $response = Index::flush();

        echo '<pre>';
        print_r($response->getData());
        die;


        $Query = \Ela\Facades\Query::setQuery(app('BoolQuery'));

        // Выпролняем запрос в индекс
        $resultSet = $index->search($Query);

        $results = [];
        if ($response = $resultSet->getResults()) {
            foreach ($response as $result) {
                $id = $result->getId();
                $row = $result->getData();
                $highlights = $result->getHighlights();
                $item = [
                        'id' => $id,
                        'highlights' => $highlights,
                    ] + $row;
                $results[] = $item;
            }
        }


        $data = [
            'total' => $resultSet->getTotalHits(),
            'results' => $results,
        ];

        echo '<pre>';
        print_r($data);
        die;

        return new JsonResponse($data, 200);
    }

}
