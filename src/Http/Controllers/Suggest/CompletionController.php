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


        $text = 'Световые фигуры Neon';
        $size = 5;
        $suggest = new Completion('suggest', $this->field_search);
        $suggest->setSize($size);

        /*$suggest->setFuzzy([
            "fuzziness" => 2
        ]);*/


        $sug = new Suggest();
        $sug->setGlobalText($text);
        $sug->addSuggestion($suggest);
        #$sug->addSuggestion($suggest2);


        $Query = Query::create($sug)->setSize($size);
        #$Query = Query::create($suggest)->setSize($size);

        $resultSet = $index->search($Query);


        $suggests = $resultSet->getSuggests();

echo '<pre>';
print_r($suggests); die;

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
                'type' => 'completion',
                'analyzer' => 'russian_english',
                'search_analyzer' => 'russian_english',
            ]
        ]));

        /*
         *   analyzer: russian_english
  search_analyzer: russian_english
        */


        $index->flush();

        $suggest = Yaml::parseFile(getenv('ES_SETTINS_PATH') . 'completion/words.yaml');
        $words = [];

        foreach ($suggest as $k => $word) {
            $words[] = new Document($k, [
                $this->field_search => $word
            ]);
        }
        $index->addDocuments($words);
        $index->refresh();
        return $index;
    }

}
