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


            $this->add('vendor_name');
            $this->add('colors');
            $this->add('collection');
            $this->add('pagetitle');
            $this->add('armature_material');
            $this->add('armature_color');
            $this->add('country_orig');
            $this->add('diffuser');
            $this->add('dopolnitelno');
            $this->add('forma');
            $this->add('forma_plafona');
            $this->add('interer');
            $this->add('krepej');
            $this->add('lamp_style');
            $this->add('lamp_type');
            $this->add('mesto_montaza');
            $this->add('mesto_prim');
            $this->add('osobennost');
            $this->add('ottenok');
            $this->add('plafond_color');
            $this->add('plafond_material');
            $this->add('sub_oc_razm');
            $this->add('tip_poverhnosti_plafonov_new');
            $this->add('colors');
            $this->add('materials');
            $this->add('forms');

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
            ->addDirectGenerator($text);


        \Ela\Facades\Suggest::addSuggestion($phraseSuggest);

        return $this;
    }
}
