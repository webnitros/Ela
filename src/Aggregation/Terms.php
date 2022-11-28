<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 24.11.2022
 * Time: 09:20
 */

namespace Ela\Aggregation;


use Ela\Facades\Map;
use Ela\Abstracts\Aggregation;
use Ela\Interfaces\AggregationInterface;
use Elastica\Param;

class Terms extends Aggregation implements AggregationInterface
{

    public function aggs(Param $Param): void
    {

        $values = Map::request()->get($this->field());
        if (!$values) {
            $values = [];
        }

        // Labels
        $Labels = new \Elastica\Aggregation\Terms('labels');
        $Labels->setField($this->field());
        $Labels->setSize(1000);
        $Labels->setOrder('_count', 'desc');
        $Labels->setMinimumDocumentCount(0);
        $Labels->setExcludeAsExactMatch($values);

        /**
         * Выбирем все выбранные значения чтобы для них посчитать результаты
         * например если выбра категория: то для нее надо посчитать количество результатов
         * так как она ИСКЛЮЧЕННАЯ то её нужно включить doc_count в selected
         */
        $Selected = new \Elastica\Aggregation\Terms('selected');
        $Selected->setField($this->field());
        $Selected->setSize(1000);
        $Selected->setOrder('_count', 'desc');
        $Selected->setMinimumDocumentCount(0);
        $Selected->setIncludeAsExactMatch($values);


        $Param->addAggregation($Labels);
        $Param->addAggregation($Selected);


    }

}
