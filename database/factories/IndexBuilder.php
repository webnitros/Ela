<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 17.11.2022
 * Time: 20:51
 */

namespace Database\Factories;

use Elastica\Client;
use Elastica\Document;
use Elastica\Mapping;
use Symfony\Component\Yaml\Yaml;

class IndexBuilder
{
    /**
     * @return \Elastica\Index
     */
    public function createIndex()
    {

        $Client = new Client(['host' => getenv('ES_HOST'), 'port' => getenv('ES_PORT')]);

        $index = $Client->getIndex(getenv('ES_INDEX_PRODUCT'));

        ######## Создаем индекс
        $index->create(['settings' => ['analysis' => $this->analysis()]], ['recreate' => true]);

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
        $Client = new Client(['host' => getenv('ES_HOST'), 'port' => getenv('ES_PORT')]);
        $index = $Client->getIndex(getenv('ES_INDEX_PRODUCT'));
        return $index->flush();
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
        $dir = getenv('ES_SETTINS_PATH');
        return Yaml::parseFile($dir . 'settings.yaml');
    }

    public function analysis()
    {
        $dir = getenv('ES_SETTINS_PATH');
        return Yaml::parseFile($dir . 'analysis.yaml');
    }

    public function mappings()
    {
        $dir = getenv('ES_SETTINS_PATH');
        return Yaml::parseFile($dir . 'mappings.yaml');
    }

}