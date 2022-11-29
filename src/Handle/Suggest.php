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

    public static function create()
    {
        if (!$suggest = MultiSearch::get('suggest')) {
            return null;
        }
        if (!$suggest->hasSuggests()) {
            return null;
        }

        $suggestes = $suggest->getSuggests();
        $words = [];
        foreach ($suggestes as $name => $suggeste) {
            foreach ($suggeste as $item) {
                foreach ($item['options'] as $word) {
                    $words[$name] = $word;
                }
            }
        }
        return $words;
    }


}
