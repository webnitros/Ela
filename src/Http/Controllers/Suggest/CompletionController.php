<?php

namespace Ela\Http\Controllers\Suggest;

use Ela\Analysis\CharFilter\TranslitToEnglish;
use Ela\Analysis\CharFilter\TranslitToRussia;
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

        #$text = 'arte';
        $text = 'точе';
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
        return (new Client(['host' => getenv('ES_HOST'), 'port' => getenv('ES_PORT')]))->getIndex('product_completion');
    }

    protected function _getIndexForTest(): Index
    {
        $index = $this->getIndex();

        $ana = [
            'analyzer' => [
                'autocomplete' => [
                    'tokenizer' => 'lowercase',
                    'filter' => [
                        'lowercase',
                        'synonym',
                        'russian_english_stopwords'
                    ]
                ],
                'autocomplete_search' => [
                    'tokenizer' => 'lowercase',
                    'filter' => [
                        'lowercase',
                        'synonym'
                    ]
                ]
            ],
            'filter' => [
                'synonym' => [
                    'type' => 'synonym',
                    'synonyms' => [
                        'точечные, встраиваемые'
                    ],
                ],
                'russian_english_stopwords' => [
                    'type' => 'stop',
                    'stopwords' => 'а,без,более,бы,был,была,были,было,быть,в,вам,вас,весь,во,вот,все,всего,всех,вы,где,да,даже,для,до,его,ее,если,есть,еще,же,за,здесь,и,из,или,им,их,к,как,ко,когда,кто,ли,либо,мне,может,мы,на,надо,наш,не,него,нее,нет,ни,них,но,ну,о,об,однако,он,она,они,оно,от,очень,по,под,при,с,со,так,также,такой,там,те,тем,то,того,тоже,той,только,том,ты,у,уже,хотя,чего,чей,чем,что,чтобы,чье,чья,эта,эти,это,я,a,an,and,are,as,at,be,but,by,for,if,in,into,is,it,no,not,of,on,or,such,that,the,their,then,there,these,they,this,to,was,will,with',
                ]
            ],
            'char_filter' => []
        ];


        $ana = (new TranslitToRussia())->update($ana);
        $ana = (new TranslitToEnglish())->update($ana);


        $index->create(['settings' => ['analysis' => $ana]], ['recreate' => true]);


        $index->setMapping(new Mapping([
            'word' => [
                'type' => 'completion',
                #'type' => 'custom',
                'analyzer' => 'autocomplete',
                'search_analyzer' => 'autocomplete_search'
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
