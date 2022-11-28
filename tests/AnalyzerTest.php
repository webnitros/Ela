<?php

namespace Tests\Feature;

use Ela\Index;
use Elastica\Document;
use Tests\TestCase;

class AnalyzerTest extends TestCase
{

    /** @test */
    public function search()
    {
        $this->postJson('/api/analyzer', [
            'analyzer' => 'search_articles_en',
            'text' => 'A2505SP-2BK',
        ])
            ->assertSuccessful();
    }

}
