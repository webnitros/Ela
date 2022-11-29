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

    /**
     * @var array|string|null
     */
    public $query;

    public function handle(Controller $controller, Request $request, Event $event): void
    {
        $query = $request->get('query');
        if (!empty($query)) {
            $this->add($query, 'vendor_name');
            $this->add($query, 'colors');
            $this->add($query, 'collection');
            $this->add($query, 'pagetitle');
            $this->add($query, 'armature_material');
            $this->add($query, 'armature_color');
            $this->add($query, 'country_orig');
            $this->add($query, 'diffuser');
            $this->add($query, 'dopolnitelno');
            $this->add($query, 'forma');
            $this->add($query, 'forma_plafona');
            $this->add($query, 'interer');
            $this->add($query, 'krepej');
            $this->add($query, 'lamp_style');
            $this->add($query, 'lamp_type');
            $this->add($query, 'mesto_montaza');
            $this->add($query, 'mesto_prim');
            $this->add($query, 'osobennost');
            $this->add($query, 'ottenok');
            $this->add($query, 'plafond_color');
            $this->add($query, 'plafond_material');
            $this->add($query, 'sub_oc_razm');
            $this->add($query, 'tip_poverhnosti_plafonov_new');
            $this->add($query, 'colors');
            $this->add($query, 'materials');
            $this->add($query, 'forms');
            #$controller->query()->setSuggest($controller->Suggest());

            $index = $controller->index();

            $Search = new Search($index->getClient());
            $Search->setSuggest($controller->Suggest());

            $controller->addSearch('suggest', $Search);
        }
    }

    public function add($query, string $field)
    {
        $suggest = new Term($field, $field);
        $suggest->setStringDistanceAlgorithm('jaro_winkler');
        # $suggest->setAnalyzer('search_articles_rus');
        \Ela\Facades\Suggest::addSuggestion($suggest->setText($query));

        # $this->add_($query, $field);

        return $this;
    }

    public function add_($query, string $field)
    {
        $phraseSuggest = (new Phrase($field . '_suggest', $field))
            ->setText($query)
            ->setAnalyzer('simple')
            ->setHighlight('<suggest>', '</suggest>')
            ->setStupidBackoffSmoothing(Phrase::DEFAULT_STUPID_BACKOFF_DISCOUNT)
            ->addCandidateGenerator(new DirectGenerator('text'));

        \Ela\Facades\Suggest::addSuggestion($phraseSuggest);
        return $this;
    }
}
