<?php

namespace Tests;


class SuggestTest extends TestCase
{

    /** @test */
    public function searchPhrase()
    {
        $res = $this->postJson('/api/suggest/phrase', [
            'text' => 'Divina',
            #'text' => 'Maytoni люстра коричнвая',
        ]);
        echo '<pre>';
        print_r($res->getContent());
        die;

        /*      ->assertSuccessful()
              ->assertJsonStructure(['total', 'results']);*/
    }


    /** @test */
    public function searchTerm()
    {
        $res = $this->postJson('/api/suggest/term', [
            'text' => 'LIANTA',
            #ALBERTINA
            #'text' => 'Maytoni люстра коричнвая',
        ]);

        echo '<pre>';
        print_r(json_decode($res->getContent(), 1));
        die;

        /*      ->assertSuccessful()
              ->assertJsonStructure(['total', 'results']);*/
    }

    /** @test */
    public function searchCompletio()
    {
        $res = $this->postJson('/api/suggest/term', [
            'text' => 'LIANTA',
            #ALBERTINA
            #'text' => 'Maytoni люстра коричнвая',
        ]);

        echo '<pre>';
        print_r(json_decode($res->getContent(), 1));
        die;

        /*      ->assertSuccessful()
              ->assertJsonStructure(['total', 'results']);*/
    }


}
