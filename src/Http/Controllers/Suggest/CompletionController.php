<?php

namespace Ela\Http\Controllers\Suggest;

use Ela\Analysis\CharFilter\TranslitToEnglish;
use Ela\Analysis\CharFilter\TranslitToRussia;
use Ela\Facades\IndexBuilder;
use Ela\Facades\Map;
use Ela\Http\Controllers\Controller;
use Ela\Templates\CompletionIndex;
use Elastica\Client;
use Elastica\Document;
use Elastica\Index;
use Elastica\Mapping;
use Elastica\Query;
use Elastica\Suggest;
use Elastica\Suggest\Completion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\Yaml\Yaml;

class CompletionController extends Controller
{

    public $field_search;

    public function get(Request $request)
    {

        $index = $this->_getIndexForTest();
        # $index = $this->getIndex();
        echo '<pre>';
        print_r(212);
        die;

        #$text = 'arte';
        $text = 'Люстра к';
        $size = 5;
        $suggest = new Completion('completion', 'word');
        $suggest->setSize($size);
        $suggest->setFuzzy([
            "fuzziness" => 2
        ]);


        $sug = new Suggest();
        $sug->setGlobalText($text);
        $sug->addSuggestion($suggest);


        $Query = Query::create($sug)->setSize($size);


        $resultSet = $index->search($Query);


        $suggests = $resultSet->getSuggests();

        $texts = [];
        foreach ($suggests['completion'][0]['options'] as $suggest) {
            $texts[] = highlightQuery($suggest['text'], $text);
        }

        return new JsonResponse($data, 200);
    }


    /**
     * @return \Elastica\Index
     */
    protected function getIndex(): Index
    {
        return (new CompletionIndex())->getIndex();
    }

    protected function _getIndexForTest(): Index
    {
        $completion = Yaml::parseFile(Map::pathSetting() . 'completion/words.yaml');
        $CompletionIndex = new CompletionIndex();
        $CompletionIndex->documents($completion);
        return $CompletionIndex->getIndex();
    }

}
