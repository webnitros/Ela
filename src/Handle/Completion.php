<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 30.11.2022
 * Time: 10:10
 */

namespace Ela\Handle;


class Completion
{

    public static function create(\Elastica\ResultSet $suggest)
    {
        $suggestes = $suggest->getSuggests();
        if (empty($suggestes['completion'][0]['options'])) {
            return null;
        }

        $words = [];
        $completion = $suggestes['completion'][0]['options'];
        foreach ($completion as $item) {
            $tmp = mb_strtolower($item['text']);
            if (!array_key_exists($tmp, $words)) {
                $words[$tmp] = $item['text'];
            }
        }
        return array_values($words);
    }
}
