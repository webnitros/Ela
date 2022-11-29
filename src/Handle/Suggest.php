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
        foreach ($suggeste as $item) {
            foreach ($item['options'] as $word) {
                $this->words[$name] = $word;
            }
        }
    }


}
