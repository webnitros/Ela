<?php

namespace Tests;


class CompletionTest extends TestCase
{

    /** @test */
    public function searchCompletio()
    {
        $this->postJson('/api/suggest/completion', [
            'text' => 'LIANTA',
            #ALBERTINA
            #'text' => 'Maytoni люстра коричнвая',
        ])
            ->assertSuccessful()
            ->assertJsonStructure(['total', 'results']);
    }


}
