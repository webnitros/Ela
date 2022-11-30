<?php

namespace Ela\Http\Controllers\Suggest;

use Ela\Facades\IndexBuilder;
use Ela\Http\Controllers\Controller;
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


        /*   $this->validatorResponse($request, [
               'text' => 'required',
           ]);
           $text = $request->get('text');*/
        #$text = 'Уличный светильник';


        $index = $this->_getIndexForTest();


        $text = 'люстр';
        #$text = 'фкеу дфьз';
        $size = 5;
        $suggest = new Completion('suggest', $this->field_search);
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
        foreach ($suggests['suggest'][0]['options'] as $suggest) {
            $texts[] = $suggest['text'];
        }
        echo '<pre>';
        print_r($texts);
        die;


        return new JsonResponse($data, 200);
    }


    protected function _getIndexForTest(): Index
    {
        $index = IndexBuilder::createIndex();
        #$index = $this->index();
        $this->field_search = 'suggest_completion';

        $index->setMapping(new Mapping([
            $this->field_search => [
                'type' => 'completion'
            ]
        ]));

        /*
         *   analyzer: russian_english
  search_analyzer: russian_english
        */


        $index->flush();

        $dir = getenv('ES_SETTINS_PATH');

        $suggest = Yaml::parseFile($dir . 'completion/words.yaml');
        $words = [];

        // Подсказки
        foreach ($suggest as $k => $word) {
            $key = 'completion_' . $k;
            $words[] = new Document($key, [
                $this->field_search => $word
            ]);
        }

        // СЛОВА ДЛЯ ИСПРАВЛЕНИЯ
        $wordComplite = true;
        if ($wordComplite) {
            $suggest = Yaml::parseFile($dir . 'suggest_word/words.yaml');
            foreach ($suggest as $k => $word) {
                $key = 'suggest_word_' . $k;
                $words[] = new Document($key, ['suggest_word' => $word]);
            }
        } else {
            $suggest = Yaml::parseFile($dir . 'suggest_word/words.yaml');
            foreach ($suggest['words'] as $k => $word) {
                $key = 'suggest_word_' . $k;
                $words[] = new Document($key, ['suggest_word' => $word]);
            }
        }


        $index->addDocuments($words);
        $index->refresh();
        return $index;
    }

}
