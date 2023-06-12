<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 29.11.2022
 * Time: 11:01
 */

namespace Ela\Analysis;


use Ela\Facades\Map;
use Symfony\Component\Yaml\Yaml;

class Synonym
{

    public static function words(array $analysis)
    {
        $dir = Map::pathSetting();
        $path = $dir . 'synonym.yaml';

        if (file_exists($path)) {
            $arrays = Yaml::parseFile($path);
            if (!empty($arrays['words'])) {
                $analysis['filter']['my_synonym_filter']['synonyms'] = $arrays['words'];
            }
        }

        return $analysis;
    }


}
