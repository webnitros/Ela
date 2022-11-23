<?php

namespace Ela\Http\Controllers\Suggest;

use Ela\Http\Controllers\Controller;
use Ela\Http\Middleware\Aggregation;
use Ela\Http\Middleware\DefaultFilter;
use Ela\Http\Middleware\DefaultSort;
use Ela\Http\Middleware\ParamsTerms;
use Ela\Http\Middleware\QueryString;
use Ela\Http\Middleware\QueryStringHighLight;
use Ela\Http\Middleware\SortScriptBall;
use Elastica\Client;
use Elastica\Document;
use Elastica\Index;
use Elastica\Mapping;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Suggest\Completion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompletionController extends Controller
{

    public function get(Request $request)
    {
        $this->validatorResponse($request, [
            'text' => 'required',
        ]);
        $text = $request->get('text');
        $size = 20;

        $suggest = new Completion('suggestVendor', 'fieldName2');
        $suggest->setPrefix($text)->setSize($size);
        #$suggest->setFuzzy([
        #    "fuzziness" => 1
        #]);

        $index = $this->_getIndexForTest();


        $Query = Query::create($suggest)->setSize($size);
        $resultSet = $index->search($Query);


        $suggests = $resultSet->getSuggests();

        $testx = [];
        $results = $suggests['suggestVendor'];


        foreach ($results[0]['options'] as $option) {
            $testx[] = $option['text'];
        }

        echo '<pre>';
        print_r($testx);
        die;

        return new JsonResponse($data, 200);
    }


    protected function _getIndexForTest(): Index
    {
        $index = $this->index();
        $index->setMapping(new Mapping([
            'fieldName2' => [
                'type' => 'completion'
            ]
        ]));
        $res = $index->flush();


        $index->addDocuments([


            /*new Document('1', [
                'fieldName' => [
                    'input' => [
                        'уличный светильник',
                        'уличный светильник arte lamp'
                    ],
                    'weight' => 7,
                ],
            ]),

            new Document('2', [
                'fieldName' => [
                    'input' => [
                        'Светильники потолочные',
                        'Светильники потолочные arte lamp'
                    ],
                    'weight' => 7,
                ],
            ]),

            new Document('3', [
                'fieldName' => [
                    'input' => [
                        'Светильники arte lamp'
                    ],
                    'weight' => 8,
                ],
            ]),

            new Document('4', [
                'fieldName' => [
                    'input' => [
                        'Светильники потолочные светодиодный',
                        'Светильники потолочные светодиодный квадратный',
                    ],
                    'weight' => 8,
                ],
            ]),

            new Document('5', [
                'fieldName' => [
                    'input' => [
                        'Светильник',
                    ],
                    'weight' => 5,
                ],
            ]),
            new Document('6', [
                'fieldName' => [
                    'input' => [
                        'Светильники потолочные квадратные',
                    ],
                    'weight' => 5,
                ],
            ]),

            new Document('7', [
                'fieldName' => [
                    'input' => [
                        'Светильники потолочные светодиодный квадратный',
                    ],
                    'weight' => 5,
                ],
            ]),*/

            new Document('9', [
                'fieldName2' => [
                    'input' => [
                        'светильник',
                    ],
                    'weight' => 20,
                ],
            ]),
            new Document('10', [
                'fieldName2' => [
                    'input' => [
                        'светильник подвесной',
                    ],
                    'weight' => 20,
                ],
            ]),
            new Document('11', [
                'fieldName2' => [
                    'input' => [
                        'светильник подвесной arte lamp',
                    ],
                    'weight' => 20,
                ],
            ]),


        ]);

        $index->refresh();

        return $index;
    }

}
