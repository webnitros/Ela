<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 24.03.2021
 * Time: 22:49
 */

namespace Tests;

use Elastica\Client;
use Elastica\Connection;
use Elastica\Index;
use Elasticsearch\Endpoints\Ingest\PutPipeline;
use Psr\Log\LoggerInterface;
use Symfony\Component\Yaml\Yaml;


abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    use \AppM\Foundation\MakesHttpRequests;

    /**
     * The Illuminate application instance.
     *
     * @var \Illuminate\Container\Container
     */
    protected $app;

    protected function setUp(): void
    {
        $app = require __DIR__ . '/../bootstrap/app.php';
        $this->app = $app;
        parent::setUp();
    }


    /**
     * @param array $params Additional configuration params. Host and Port are already set
     */
    protected function _getClient(array $params = [], ?callable $callback = null, ?LoggerInterface $logger = null): Client
    {
        $config = [
            'host' => $this->_getHost(),
            'port' => $this->_getPort(),
        ];

        $config = \array_merge($config, $params);

        return new Client($config, $callback, $logger);
    }

    protected function _getHost(): string
    {
        return \getenv('ES_HOST') ?: Connection::DEFAULT_HOST;
    }

    protected function _getPort(): int
    {
        return \getenv('ES_PORT') ?: Connection::DEFAULT_PORT;
    }

    protected function _getProxyUrl(): string
    {
        $proxyHost = \getenv('PROXY_HOST') ?: Connection::DEFAULT_HOST;

        return 'http://' . $proxyHost . ':8000';
    }

    protected function _getProxyUrl403(): string
    {
        $proxyHost = \getenv('PROXY_HOST') ?: Connection::DEFAULT_HOST;

        return 'http://' . $proxyHost . ':8001';
    }

    protected function _createIndex(?string $name = null, bool $delete = true, int $shards = 1): Index
    {
        $name = $name ?: static::buildUniqueId();

        $client = $this->_getClient();
        $index = $client->getIndex($name);

        $index->create(['settings' => ['index' => ['number_of_shards' => $shards, 'number_of_replicas' => 1]]], [
            'recreate' => $delete,
        ]);

        return $index;
    }

    protected static function buildUniqueId(): string
    {
        return \preg_replace('/[^a-z]/i', '', \strtolower(static::class) . \uniqid());
    }

    protected function _createRenamePipeline(): void
    {
        $client = $this->_getClient();

        // TODO: Use only PutPipeline when dropping support for elasticsearch/elasticsearch 7.x
        $endpoint = \class_exists(PutPipeline::class) ? new PutPipeline() : new Put();
        $endpoint->setID('renaming');
        $endpoint->setBody([
            'description' => 'Rename field',
            'processors' => [
                [
                    'rename' => [
                        'field' => 'old',
                        'target_field' => 'new',
                    ],
                ],
            ],
        ]);

        $client->requestEndpoint($endpoint);
    }


    protected function _waitForAllocation(Index $index): void
    {
        do {
            $state = $index->getClient()->getCluster()->getState();
            $indexState = $state['routing_table']['indices'][$index->getName()];

            $allocated = true;
            foreach ($indexState['shards'] as $shards) {
                foreach ($shards as $shard) {
                    if ('STARTED' !== $shard['state']) {
                        $allocated = false;
                    }
                }
            }
        } while (!$allocated);
    }

    protected function loadMapping($filename)
    {

        $value = Yaml::parseFile(dirname(__FILE__, 2) . '/data/mapping/' . $filename . '/fields.yaml');

        $arrays = $value['fields'];

        #$store = array_flip($arrays['store']);
        #$fieldMeta = $arrays['fieldMeta'];

        $fields = [];
        foreach ($arrays as $type => $array) {

            foreach ($array as $field => $param) {
                $data = [
                    'type' => $type,
                ];
                if (is_array($param)) {
                    foreach ($param as $arg) {
                        $data[$arg] = true;
                    }
                }
                if (!empty($array['fielddata'])) {
                    $data['fielddata'] = true;
                }
                $fields[$field] = $data;
            }
        }

        /*if (!empty($arrays['aggregation'])) {
            foreach ($arrays['aggregation'] as $field) {
                echo '<pre>';
                print_r($field);
                die;

            }
        }
        */

        if (!empty($value['fielddata']) && is_array($value['fielddata'])) {
            foreach ($value['fielddata'] as $field => $v) {
                $fields[$field]['fielddata'] = true;
            }
        }


        return $fields;
    }

    protected function loadProduct($filename)
    {
        $path = dirname(__FILE__, 2) . '/data/products/' . $filename . '.json';

        if (!file_exists($path)) {
            throw new \Exception('Файл не найден ' . $path);
        }

        $content = file_get_contents($path);
        $content = json_decode($content, true);
        if (!is_array($content)) {
            throw new \Exception('не удалось декодировать файл ' . $path);
        }
        return $content;
    }
}
