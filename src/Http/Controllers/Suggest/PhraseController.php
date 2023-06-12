<?php

namespace Ela\Http\Controllers\Suggest;

use Ela\Facades\IndexBuilder;
use Ela\Facades\Map;
use Ela\Http\Controllers\Controller;
use Elastica\Document;
use Elastica\Suggest;
use Elastica\Suggest\CandidateGenerator\DirectGenerator;
use Elastica\Suggest\Phrase;
use Elastica\Suggest\Term;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\Yaml\Yaml;

class PhraseController extends Controller
{

    public function get(Request $request)
    {
        /*$query = 'Arte Lamx';
        #$query = 'Серебристый';

        $field = 'vendor_name';

        $suggest = new \Elastica\Suggest();

        $suggest->setGlobalText($query);


        $Term = new Term('suggest_' . $field, $field);
        $Term->setStringDistanceAlgorithm('jaro_winkler');

        $Term->setAnalyzer('simple');

        $suggest->addSuggestion($Term);

        $index = $this->_getIndexForTest();
        $result = $index->search($suggest);
        $response = $result->getSuggests();

        echo '<pre>';
        print_r($response);
        die;*/


        /* $this->validatorResponse($request, [
             'text' => 'required',
         ]);

        $text = $request->get('text');*/

        $query = 'ручки Накладно';
        #$query = 'arte lamp musxra матовый';
        #$query = 'Arte Lxmx MUSxRA Мxтовый';


        $field = 'suggest_word.trigram';

        $text = new DirectGenerator($field);
        $text->setSuggestMode($text::SUGGEST_MODE_ALWAYS);
        $phraseSuggest = (new Phrase('suggest_word', $field))
            ->setAnalyzer('simple')
            ->setGramSize(3)
            ->addDirectGenerator($text);


        $suggest = (new Suggest())
            ->setGlobalText($query)
            ->addSuggestion($phraseSuggest);

        #$index = $this->index();

        $index = $this->_getIndexForTest();

        $result = $index->search($suggest);
        $suggests = $result->getSuggests();


        echo '<pre>';
        print_r($suggests);
        die;


        return new JsonResponse($data, 200);
    }

    protected function _getIndexForTest()
    {
        $dir = Map::pathSetting();
        $in = true;

        if ($in) {
            $suggest = Yaml::parseFile($dir . 'suggest_word/words.yaml');
            $words = [];
            foreach ($suggest['words'] as $k => $word) {
                $words[] = new Document($k, ['suggest_word' => $word]);
            }

            $index = IndexBuilder::createIndexAddDocuemnts();
            $index->addDocuments($words);
        } else {
            $index = IndexBuilder::createIndex();
        }

        $index->refresh();
        return $index;
    }

}
