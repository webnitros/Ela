<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 29.11.2022
 * Time: 11:01
 */

namespace Ela\Analysis;


use Symfony\Component\Yaml\Yaml;

class StopWords
{

    public static function words(array $analysis)
    {
        $dir = getenv('ES_SETTINS_PATH');
        $path = $dir . 'stop_words.yaml';


        if (file_exists($path)) {
            $arrays = Yaml::parseFile($path);

            $words = null;

            if (!empty($arrays['words'])) {
                $words = $arrays['words'];
            }

            if (!empty($arrays['keywords'])) {
                $words = array_merge($words, $arrays['keywords']);
            }

            if ($words) {
                $analysis['filter']['russian_english_stopwords']['stopwords'] = implode(',', $words);
            }

        }

        return $analysis;
    }


}
