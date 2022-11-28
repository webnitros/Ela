<?php

namespace Tests;

use Tests\TestCase;

class SearchTest extends TestCase
{

    /** @test */
    public function search()
    {
        $res = $this->postJson('/api/search', [
            'shop_availability' => [1, 3, 4],
            'published' => '1',
        ])
           /* ->assertSuccessful()
            ->assertJsonStructure(['total', 'results'])*/;

            echo '<pre>';
            print_r($res->getContent()); die;

    }


    /** @test */
    public function searchQuery()
    {
        $this->postJson('/api/search', [
            'query' => 'arte',
        ])
            ->assertSuccessful()
            ->assertJsonStructure(['total', 'results']);
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
