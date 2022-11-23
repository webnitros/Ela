<?php
/**
 * Поиск по ключевым словам
 */

namespace Ela\Http\Middleware;

use Ela\Facades\BoolQuery;
use Ela\Http\Controllers\Controller;
use Ela\Interfaces\Middleware;
use Elastica\Query\MultiMatch;
use Illuminate\Http\Request;
use Symfony\Contracts\EventDispatcher\Event;

class QueryString implements Middleware
{

    public function handle(Controller $controller, Request $request, Event $event): void
    {

        if ($request->has('query')) {

            $query = $this->parse($request->get('query'));
            $request->query('query', $query);


            // Валидация запроса
            $controller->validatorResponse($request, [
                'query' => 'required|min:3|max:255'
            ]);


            $MultiMatch = \Ela\Facades\MultiMatch::setQuery($query)
                ->setType('cross_fields')
                ->setFields([
                    "artikul_1c^650",
                    "show_artikul^650",
                    "show_artikul.org^2000",
                    "show_artikul.keyword^250",
                    "show_artikul.keyword_rus^250",
                    "show_artikul.keyword_en^250",
                    "*.org^10",
                    "*.raw^5",
                    "*.ng",
                    "pagetitle.org^250",
                    "pagetitle.raw^125",
                    "pagetitle.ng^1",
                    "collection.search^3",
                    "category.search",
                    "sub_category.search^1",
                    "vendor_name.search^1",
                    "interer",
                    "color.search^2",
                    "armature_color.search^2",
                    "plafond_color.search^2",
                    "plafond_material.search^1",
                    "armature_material.search^1",
                    "lamp_type.search^1",
                    "forma_plafona.search^1",
                    "krepej.search^1",
                    "pu_dimmer.search^1",
                    "lamp_socket.search^1",
                    "lamp_style.search^2",
                    "category.search"
                ])
                ->setOperator('or')
                #->setOperator('and')
                ->setTieBreaker(1)
                ->setMinimumShouldMatch('80%');


            #$controller->BoolQuery()->addShould($multiMatch);
            BoolQuery::addMust($MultiMatch);
            #$controller->BoolQuery()->addFilter($multiMatch);
            #app('BoolQuery')->addMust($multiMatch);
        }

    }

    public function parse($query)
    {
        $query = str_ireplace("'", ' ', $query);
        $query = str_ireplace("(", ' ', $query);
        $query = str_ireplace(")", ' ', $query);

        $query = htmlspecialchars_decode($query, ENT_QUOTES);
        $query = strip_tags($query);
        $query = preg_replace('#["\'\(\)\[\]\{\}]#', '', $query);
        $query = htmlspecialchars($query);
        $query = trim($query);
        $query = preg_replace('/[^_-а-яёa-z0-9\s\.\/\[\]]+/iu', ' ', $this->stripTags($query));
        return $query;
    }

    /**
     * @var array An array of regex patterns regulary cleansed from content.
     */
    public $sanitizePatterns = array(
        'scripts' => '@<script[^>]*?>.*?</script>@si',
        'entities' => '@&#(\d+);@',
        'tags1' => '@\[\[(.*?)\]\]@si',
        'tags2' => '@(\[\[|\]\])@si',
    );

    /**
     * Strip unwanted HTML and PHP tags and supplied patterns from content.
     *
     * @param string $html The string to strip
     * @param string $allowed An array of allowed HTML tags
     * @param array $patterns An array of patterns to sanitize with; otherwise will use modX::$sanitizePatterns
     * @param int $depth The depth in which the parser will strip given the patterns specified
     * @return boolean True if anything was stripped
     * @see modX::$sanitizePatterns
     */
    public function stripTags($html, $allowed = '', $patterns = array(), $depth = 10)
    {
        $stripped = strip_tags($html, $allowed);
        if (is_array($patterns)) {
            if (empty($patterns)) {
                $patterns = $this->sanitizePatterns;
            }
            foreach ($patterns as $pattern) {
                $depth = ((integer)$depth ? (integer)$depth : 10);
                $iteration = 1;
                while ($iteration <= $depth && preg_match($pattern, $stripped)) {
                    $stripped = preg_replace($pattern, '', $stripped);
                    $iteration++;
                }
            }
        }
        return $stripped;
    }
}
