<?php

namespace Ela\Http\Controllers\Suggest;

use Ela\Http\Controllers\Controller;
use Ela\Index;
use Elastica\Document;
use Elastica\Suggest;
use Elastica\Suggest\CandidateGenerator\DirectGenerator;
use Elastica\Suggest\Phrase;
use Elastica\Suggest\Term;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TermController extends Controller
{

    public function get(Request $request)
    {
        $this->validatorResponse($request, [
            'text' => 'required',
        ]);

        $text = $request->get('text');


        $suggest = new Suggest();
        $suggest1 = new Term('suggest1', 'collection');
        $suggest->addSuggestion($suggest1->setText($text));


        #$suggest2 = new Term('suggest2', 'text');
        #$suggest->addSuggestion($suggest2->setText($text));


        #$suggest2->setStringDistanceAlgorithm('jaro_winkler');


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
        $index = Index::createIndexAddDocuemnts();

        /*$index->addDocuments([
            new Document('1', ['id' => 1, 'text' => 'ALBERTINA']),
            new Document('2', ['id' => 1, 'text' => 'Maytoni']),
            new Document('3', ['id' => 1, 'text' => 'коричневая']),
            new Document('4', ['id' => 1, 'text' => 'Food']),
            new Document('5', ['id' => 1, 'text' => 'Flood']),
            new Document('6', ['id' => 1, 'text' => 'Folks']),
            new Document('7', ['id' => 1, 'text' => 'A1664PL-5CC']),
        ]);*/

        #Люстра Bogates ALBERTINA 248/8 4690389088179

        /*  $index->addDocuments([
              new Document('1', ['id' => 1, 'text' => 'GitHub']),
              new Document('2', ['id' => 1, 'text' => 'Elastic']),
              new Document('3', ['id' => 1, 'text' => 'Search']),
              new Document('4', ['id' => 1, 'text' => 'Food']),
              new Document('5', ['id' => 1, 'text' => 'Flood']),
              new Document('6', ['id' => 1, 'text' => 'Folks']),
          ]);*/
        $index->refresh();
        return $index;
    }

}
