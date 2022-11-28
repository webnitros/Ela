<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 24.11.2022
 * Time: 14:13
 */

namespace Ela\Traintes;

use Ela\Facades\Map;
use Ela\Facades\MultiSearch;

trait ResultsTrait
{

    public function aggregations(\Elastica\ResultSet $result_set, bool $build = false)
    {
        $aggregations = $result_set->getAggregations();
        foreach ($aggregations as $field => $aggregation) {
            $Result = Map::aggResult($field);


            $Result->setDocCount($aggregation['doc_count']);
            $Result->setValues($aggregation);


            if ($build) {
                $Result->setValuesFilter($aggregation);
            }


        }
        return Map::getResults();
    }


    public function products()
    {
        $results = [];
        if ($Products = MultiSearch::get('products')) {
            if ($response = $Products->getResults()) {
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
        }

        return $results;
    }


}
