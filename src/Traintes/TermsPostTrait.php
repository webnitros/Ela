<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 24.11.2022
 * Time: 14:13
 */

namespace Ela\Traintes;

use Elastica\Query\Ids;
use Elastica\Query\Range;
use Elastica\Query\Term;
use Elastica\Query\Terms;
use Illuminate\Http\Request;

trait TermsPostTrait
{
    /**
     * @var \Illuminate\Http\Request
     */
    private Request $request;

    public function addTerms($field, $values = null)
    {
        if ($this->request->has($field)) {
            if ($values || $values = $this->request->get($field)) {
                \Ela\Facades\PostFilter::addFilter((new Terms($field))->setTerms($values));
            }
        }
        return $this;
    }


    public function addRange($field, $values = null)
    {
        if ($this->request->has($field)) {
            if ($values || $values = $this->request->get($field)) {
                $Range = new Range($field, $values);
                \Ela\Facades\PostFilter::addFilter($Range);
            }
        }
        return $this;
    }

    public function addTerm($field, $value = null)
    {
        if ($this->request->has($field)) {
            if ($value || $value = $this->request->get($field)) {
                \Ela\Facades\PostFilter::addFilter((new Term())->setTerm($field, $value));
            }
        }
        return $this;
    }


    public function addBoolean(string $field, $value = null)
    {
        if ($this->request->has($field)) {
            $c = !is_null($value) ? $value : $this->request->boolean($field);
            \Ela\Facades\PostFilter::addFilter((new Term())->setTerm($field, $c));
        }
        return $this;
    }

    private function addIds(string $field, $ids = null)
    {
        if ($this->request->has($field)) {
            if ($ids || $ids = $this->request->get($field)) {
                \Ela\Facades\PostFilter::addFilter(new Ids($ids));
            }
        }
        return $this;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }
}
