<?php

namespace Ela\Http\Controllers\Suggest;

use Ela\Facades\Index;
use Ela\Http\Controllers\Controller;
use Elastica\Document;
use Elastica\Suggest;
use Elastica\Suggest\CandidateGenerator\DirectGenerator;
use Elastica\Suggest\Phrase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PhraseController extends Controller
{

    public function get(Request $request)
    {

        /* $this->validatorResponse($request, [
             'text' => 'required',
         ]);

         $text = $request->get('text');*/
        $text = new DirectGenerator('text');

        $phraseSuggest = (new Phrase('suggest1', 'text'))
            ->setText('Maytoni')
            #->setText('юстра')
            #->setText('юстра Maytoni Ring MOD013PL-02B')
            #->setText('люстра 4010 lianet')
            #->setText('Maytoni люстра коричнвая')
            ->setAnalyzer('simple')
            ->setHighlight('<suggest>', '</suggest>')
            ->setStupidBackoffSmoothing(Phrase::DEFAULT_STUPID_BACKOFF_DISCOUNT)
            ->addDirectGenerator(new DirectGenerator('text'));


        $suggest = (new Suggest())
            ->addSuggestion($phraseSuggest);


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

        $index->addDocuments([
            new Document('1', ['text' => 'Maytoni люстра коричневая']),
            new Document('2', ['text' => 'Люстра Divinare LIANTO']),
            new Document('3', ['text' => 'Люстра Divinare LIANTO 4010/02 PL-3']),
            new Document('4', ['text' => 'Светильник Maytoni РИНГ MOD013PL-02B']),
            new Document('5', ['text' => 'Люстра']),
            new Document('6', ['text' => 'Maytoni']),
        ]);
        $index->refresh();
        return $index;
    }

}
