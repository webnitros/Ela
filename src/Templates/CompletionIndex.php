<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 02.12.2022
 * Time: 05:26
 */

namespace Ela\Templates;


use Ela\Analysis\CharFilter\TranslitToEnglish;
use Ela\Analysis\CharFilter\TranslitToRussia;
use Elastica\Client;
use Elastica\Document;
use Elastica\Index;
use Elastica\Mapping;

class CompletionIndex
{
    /**
     * @var \Elastica\Index
     */
    private Index $index;

    public function __construct()
    {
        $Client = new Client(['host' => getenv('ES_HOST'), 'port' => getenv('ES_PORT')]);
        $this->index = $Client->getIndex('product_completion');
    }

    /**
     * @return \Elastica\Index
     */
    public function getIndex(): Index
    {
        return $this->index;
    }

    public function documents(array $inputs): Index
    {
        $index = $this->getIndex();

        $words = [];

        // Подсказки
        foreach ($inputs as $k => $word) {
            $key = 'completion_' . $k;
            $words[] = new Document($key, [
                'word' => $word
            ]);
        }
        $index->addDocuments($words);
        $index->refresh();

        return $index;
    }

    protected function createIndex(): Index
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
        return $index;
    }
}
