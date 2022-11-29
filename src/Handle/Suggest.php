<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 29.11.2022
 * Time: 08:35
 */

namespace Ela\Handle;


use Ela\Facades\MultiSearch;

class Suggest
{
    protected $words = [];

    public static function create(\Elastica\ResultSet $suggest)
    {

        if (!function_exists('suggest_sort')) {
            function suggest_sort($a, $b)
            {
                if (isset($a['score']) and isset($b['score'])) {
                    return $a['score'] <=> $b['score'];
                }
                return -1;
            }
        }


        $suggestes = $suggest->getSuggests();
        $Suggest = new Suggest();
        if (count($suggestes) > 0) {
            if (!empty($suggestes['suggest_word'])) {
                $suggest_word = $suggestes['suggest_word'];
                unset($suggestes['suggest_word']);
                $Suggest->words('suggest_word', $suggest_word);
            }

            foreach ($suggestes as $name => $suggeste) {
                $Suggest->words($name, $suggeste);
            }
        }
        return $Suggest->getWords();
    }

    public function getWords()
    {
        return $this->words;
    }

    public function words(string $name, array $suggeste)
    {
        $words = [];
        $word = null;
        foreach ($suggeste as $item) {
            foreach ($item['options'] as $frag) {
                $words[] = $frag;
            }
        }
        if (count($words) > 0) {
            usort($words, '\Ela\Handle\Suggest::suggest_sort');
            $word = array_pop($words);
        }

        $this->words[$name] = $word;
        return false;
    }

    public static function suggest_sort($a, $b)
    {
        if (isset($a['score']) and isset($b['score'])) {
            return $a['score'] <=> $b['score'];
        }
        return -1;
    }

}
