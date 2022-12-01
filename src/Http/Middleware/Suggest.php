<?php
/**
 * Параметры для поиска по умолчанию
 */

namespace Ela\Http\Middleware;

use Ela\Http\Controllers\Controller;
use Ela\Interfaces\Middleware;
use Elastica\Query;
use Elastica\Search;
use Elastica\Suggest\CandidateGenerator\DirectGenerator;
use Elastica\Suggest\Completion;
use Elastica\Suggest\Phrase;
use Elastica\Suggest\Term;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;

class Suggest implements Middleware
{
    public function handle(Controller $controller, Request $request, Event $event): void
    {
        $query = $request->get('query');
        if (!empty($query)) {

            $controller->Suggest()->setGlobalText($query);

            ##############################
            ###### Для целых предложений
            ##############################
            $this->seggest_offer();
            ##############################
            ##############################

            $index = $controller->index();

            $Search = new Search($index->getClient());
            $Search->setSuggest($controller->Suggest());
            $controller->addSearch('suggest', $Search);
        }
    }

    public function add(string $field)
    {
        $suggest = new Term($field, $field);
        # $suggest->setMinWordLength(4); // минимальная длина термина которая должна быть включена
        #$suggest->setMinDocFrequency(10); // число фрагментов где слово совпало
        $suggest->setStringDistanceAlgorithm('jaro_winkler');
        # $suggest->setAnalyzer('search_articles_rus');
        \Ela\Facades\Suggest::addSuggestion($suggest);
        return $this;
    }


    public function seggest_offer()
    {
        // Поле куда скидываются все значения
        $field = 'suggest_word.trigram';

        $text = new DirectGenerator($field);
        $text->setSuggestMode($text::SUGGEST_MODE_ALWAYS);

        $phraseSuggest = (new Phrase('suggest_word', $field))
            ->setAnalyzer('simple')
            ->setGramSize(3)
            ->addDirectGenerator($text)
            ->setStupidBackoffSmoothing(Phrase::DEFAULT_STUPID_BACKOFF_DISCOUNT);


        \Ela\Facades\Suggest::addSuggestion($phraseSuggest);

        return $this;
    }
}
