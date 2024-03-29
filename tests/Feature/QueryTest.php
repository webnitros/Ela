<?php

namespace Tests\Feature;

use Ela\Facades\Index;
use Tests\TestCase;

class QueryTest extends TestCase
{

    /** @test */
    public function search()
    {
        $this->postJson('/api/search', [
            'marker' => [
                'out_of_stock'
            ],
            'published' => '1',
        ])->assertSuccessful()
            ->assertJsonStructure([
                'total',
                'results',
                'params',
                'aggregations',
            ]);

    }


    /** @test */
    public function searchQueryInaccurateSearch()
    {
        $this->postJson('/api/search', [
            #'query' => 'ARTE SAMP',
            'query' => 'Уличноx освещение',
            # 'query' => 'синия',
            #'query' => 'LdRGs',
            #'query' => 'Arte Lamp LARGO',
            #  'query' => 'Arte Lamp LARGO',
            #'query' => 'A3320SP-1WH Arte Lamp CUINa',
            #'published' => '1',
            # 'inaccurate_search' => '1', // Не точный поиск
        ])
            ->assertSuccessful()
            ->assertJsonStructure(['total', 'results']);
    }


    /** @test */
    public function searchQueryMarker()
    {
        $this->postJson('/api/search', [
            'published' => '1',
            'shop_availability' => [
                12,
                59,
                88,
                271
            ],
            'out_of_stock' => true,
            #'in_order' => true,
            'marker' => [
                'availability',
                'new'
            ]
        ])
            ->assertSuccessful()
            ->assertJsonStructure(['total', 'results']);
    }


    /** @test */
    public function searchQuery()
    {
        $this->postJson('/api/search', [
            'query' => 'arte',
            'published' => '1',
        ])
            ->assertSuccessful()
            ->assertJsonStructure(['total', 'results']);
    }

    /** @test */
    public function searchCompletion()
    {
        $res = $this->postJson('/api/search', [
            'query' => 'arte',
            'published' => '1',
        ]);
        # ->assertSuccessful()
        # ->assertJsonStructure(['total', 'results']);

        $res = json_decode($res->getContent(), 1)['completion'];

        echo '<pre>';
        print_r(array_unique($res));
        die;

    }


    /** @test */
    public function searchQueryEmpty()
    {
        $this->postJson('/api/search', [
            'query' => '',
        ])
            ->assertStatus(422)
            ->assertJsonFragment(['message' => 'The given data was invalid.']);;
    }


    /** @test */
    public function searchQueryComplition()
    {
        $this->postJson('/api/search', [
            'query' => 'Свети',
        ])
            ->assertSuccessful()
            ->assertJsonStructure(['total', 'completion']);
    }


    /** @test */
    public function searchQueryAgregation()
    {
        $this->postJson('/api/search', [
            'published' => '1',
        ])
            ->assertSuccessful()
            ->assertJsonFragment(['message' => 'The given data was invalid.']);;
    }


    /** @test */
    public function searchTermsCreateIndex()
    {

        Index::createIndexAddDocuemnts();


        $this->postJson('/api/search', [
            'vendor' => 3,
        ])
            ->assertSuccessful()
            ->assertJsonFragment(['message' => 'The given data was invalid.']);;
    }

    /** @test */
    public function searchQueryCreateIndex()
    {

        #Index::createIndexAddDocuemnts();

        $this->postJson('/api/search', [
            'vendor' => 3,
            'query' => 'Divinare',
        ])
            ->assertSuccessful()
            ->assertJsonFragment(['message' => 'The given data was invalid.']);;
    }

    /** @test */
    public function searchQueryStr()
    {

        #Index::createIndexAddDocuemnts();

        $this->postJson('/api/search', [
            'vendor' => 7,
            'query' => 'Люстра 4010 LIANT',
        ])
            ->assertSuccessful()
            ->assertJsonFragment(['message' => 'The given data was invalid.']);;
    }

}
