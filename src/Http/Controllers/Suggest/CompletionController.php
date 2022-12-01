<?php

namespace Ela\Http\Controllers\Suggest;

use Ela\Facades\IndexBuilder;
use Ela\Http\Controllers\Controller;
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

        #$index = $this->_getIndexForTest();
        $index = $this->getIndex();

        $text = 'Arte ';
        $size = 5;
        $suggest = new Completion('completion', 'word');
        $suggest->setSize($size);
        $suggest->setFuzzy([
            "fuzziness" => 1
        ]);


        $sug = new Suggest();
        $sug->setGlobalText($text);
        $sug->addSuggestion($suggest);

        $Query = Query::create($sug)->setSize($size);

        $resultSet = $index->search($Query);


        $suggests = $resultSet->getSuggests();

        $texts = [];
        foreach ($suggests['completion'][0]['options'] as $suggest) {
            $texts[] = $suggest['text'];
        }
        echo '<pre>';
        print_r($texts);
        die;

        return new JsonResponse($data, 200);
    }

    /**
     * @return \Elastica\Index
     */
    protected function getIndex(): Index
    {
        return (new Client(['host' => getenv('ES_HOST'), 'port' => getenv('ES_PORT')]))->getIndex(getenv('ES_INDEX_COMPLITION'));
    }

    protected function _getIndexForTest(): Index
    {
        $index = $this->getIndex();
        $index->create([], ['recreate' => true]);
        $index->setMapping(new Mapping([
            'word' => [
                'type' => 'completion'
            ]
        ]));
        $index->flush();

        // Директория с папкой
        $dir = getenv('ES_SETTINS_PATH');


        $completion = Yaml::parseFile($dir . 'completion/words.yaml');

        $words = [];

        // Подсказки
        foreach ($completion as $k => $word) {
            $key = 'completion_' . $k;
            $words[] = new Document($key, [
                'word' => $word
            ]);
        }

        // СЛОВА ДЛЯ ИСПРАВЛЕНИЯ
        /*$wordComplite = true;
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
        }*/

        $index->addDocuments($words);
        $index->refresh();
        return $index;
    }

}
