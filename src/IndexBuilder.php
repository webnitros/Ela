<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 17.11.2022
 * Time: 20:51
 */

namespace Ela;

use Ela\Analysis\CharFilter\TranslitToEnglish;
use Ela\Analysis\CharFilter\TranslitToRussia;
use Ela\Analysis\StopWords;
use Ela\Analysis\Synonym;
use Ela\Facades\Map;
use Elastica\Client;
use Elastica\Document;
use Elastica\Mapping;
use Symfony\Component\Yaml\Yaml;

class IndexBuilder
{
    /**
     * @var \Elastica\Client
     */
    public Client $client;

    public function __construct($index = null, $host = null, $port = null)
    {
        $this->host = $host ?? getenv('ES_HOST');
        $this->port = $port ?? getenv('ES_PORT');
        $this->index = $index ?? getenv('ES_INDEX_PRODUCT');
        $this->client = new Client(['host' => $this->host, 'port' => $this->port]);
    }

    public function client()
    {
        return $this->client;
    }

    public function index()
    {
        return $this->client()->getIndex($this->index);
    }

    public function removeIndex()
    {
        $index = $this->index();
        $index->delete();
        return $index;
    }

    /**
     * @return \Elastica\Index
     */
    public function createIndex($settings = null)
    {
        $index = $this->index();
        $default = [
            'settings' => [
                'analysis' => $this->analysis()
            ]
        ];

        if (is_array($settings)) {
            $default['settings'] = array_merge($default['settings'], $settings);
        }

        ######## Создаем индекс
        $index->create($default, ['recreate' => true]);

        ######## Добавляем карты полей
        $data = $this->mappings();

        $mapping = new Mapping($data);
        $index->setMapping($mapping);
        return $index;
    }

    /**
     * @return \Elastica\Response
     */
    public function flush()
    {
        return $this->index()->flush();
    }

    public function createIndexAddDocuemnts()
    {
        $index = $this->createIndex();

        //  'shop_availability' => [1, 3, 4],
        $index->addDocuments([
            new Document('128768', [
                'availability' => true,
                'published' => true,
                'vendor' => 3,
                'category_name' => 'Светильники',
                'show_artikul' => 'A2505SP-2BK',
                'pagetitle' => 'Светильники Arte Lamp LARGO A2505SP-2BK',
                'pagetitle_not_artikul' => 'Светильники Arte Lamp LARGO',
                'collection' => 'LARGO',
                'shop_availability' => [
                    1, 3, 4,
                    12,
                    59,
                    88,
                    271
                ]
            ]),
            new Document('128711', [
                'availability' => true,
                'published' => true,
                'vendor' => 7,
                'show_artikul' => '4010/02 PL-3',
                'category_name' => 'Люстры',
                'pagetitle' => 'Люстра Divinare LIANTO 4010/02 PL-3',
                'pagetitle_not_artikul' => 'Люстра Divinare LIANTO',
                'collection' => 'LIANTO',
                'shop_availability' => [
                    1, 3, 4,
                    12,
                    59,
                    88,
                    271
                ]
            ]),

            new Document('128018', [
                'availability' => true,
                'published' => true,
                'vendor' => 7,
                'show_artikul' => '4010/02 PL-3',
                'category_name' => 'Люстры',
                'pagetitle' => 'Люстра Divinare 4010/02 PL-3',
                'pagetitle_not_artikul' => 'Люстра Divinare',
                'collection' => 'ALBERTINA',
                'shop_availability' => [
                    1, 3, 4,
                    12,
                    59,
                    88,
                    271
                ]
            ]),
        ]);
        $index->refresh();

        return $index;
    }

    public function settings()
    {
        $dir = Map::pathSetting();
        return Yaml::parseFile($dir . 'settings.yaml');
    }

    public function analysis()
    {
        $dir = Map::pathSetting();
        $analysis = Yaml::parseFile($dir . 'analysis.yaml');
        $analysis = Synonym::words($analysis);
        $analysis = StopWords::words($analysis);
        $analysis = (new TranslitToRussia())->update($analysis);
        $analysis = (new TranslitToEnglish())->update($analysis);
        return $analysis;
    }

    public function mappings()
    {
        $dir = Map::pathSetting();
        return Yaml::parseFile($dir . 'mappings.yaml');
    }

}
