<?php

namespace Ela\Http\Controllers;

use Elastica\Index;
use Elastica\Multi\Search as MultiSearch;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Terms;
use Elastica\Search;
use Elastica\Suggest;
use Illuminate\Http\Request;


abstract class Controller extends \AppM\Http\Controllers\Controller
{
    protected array $searchs = [];

    public function newSearch(string $name, Query $query)
    {
        $Query = new Query($query->toArray());

        //  Создаем запрос на получения Агрегаий
        $client = $this->index()->getClient();
        $Search = new Search($client);
        $Search->setQuery($Query);

        // Добавляем запрос
        $this->addSearch($name, $Search);

        return $this;
    }

    public function resetSearchs()
    {
        $this->searchs = [];
    }

    public function addSearch(string $name, Search $search, $index = null)
    {
        $this->searchs[$name] = [
            'search' => $search,
            'index' => $index,
        ];
    }


    /**
     * @return \Elastica\Multi\Search
     */
    public function createSearch()
    {
        $index = $this->index();
        $client = $index->getClient();
        $multiSearch = new MultiSearch($client);
        /* @var \Elastica\Search $search */
        foreach ($this->searchs as $key => $meta) {
            $search = $meta['search'];
            $CustomIndex = $meta['index'] ?? $index;
            // Указываем в каком индексе будет поиск
            $search->addIndex($CustomIndex);

            $multiSearch->addSearch($search, $key);
        }
        return $multiSearch;
    }


    /**
     * @return Query
     */
    public function query()
    {
        return app('Query');
    }

    /**
     * @return Index
     */
    public function index()
    {
        return app('Index');
    }

    /**
     * @return BoolQuery
     */
    public function BoolQuery()
    {
        return app('BoolQuery');
    }

    /**
     * @return BoolQuery
     */
    public function PostFilter()
    {
        return app('PostFilter');
    }

    /**
     * @return Suggest
     */
    public function Suggest()
    {
        return app('Suggest');
    }

    public function storages(Request $request)
    {
        if (!$storages = $request->get('shop_availability')) {
            $storages = $request->get('storages');
        }
        return new Terms('shop_availability', $storages);
    }

}
