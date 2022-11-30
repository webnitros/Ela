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

        $completion = $suggestes['completion'][0]['options'];
        $words = [];
        foreach ($completion as $item) {
            $words[] = $item['text'];
        }
        return $words;
    }
}
