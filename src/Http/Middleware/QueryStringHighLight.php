<?php
/**
 * Поиск по ключевым словам
 */

namespace Ela\Http\Middleware;

use AppM\Interfaces\ControllerInterface;
use AppM\Interfaces\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent as Event;

class QueryStringHighLight implements Middleware
{

    public function handle(ControllerInterface $controller, Request $request, Event $event): void
    {
        if ($request->has('query')) {

            // Подсветка искомых фраз
            \Ela\Facades\Query::setHighlight([
                'pre_tags' => ['<em class="highlight">'],
                'post_tags' => ['</em>'],
                'fields' => [
                    'article' => [
                        'fragment_size' => 200,
                        'number_of_fragments' => 1,
                    ],
                    'article.org' => [
                        'fragment_size' => 200,
                        'number_of_fragments' => 1,
                    ],
                    'article.keyword' => [
                        'fragment_size' => 200,
                        'number_of_fragments' => 1,
                    ],
                    'article.keyword_rus' => [
                        'fragment_size' => 200,
                        'number_of_fragments' => 1,
                    ],
                    'article.keyword_en' => [
                        'fragment_size' => 200,
                        'number_of_fragments' => 1,
                    ],
                    'pagetitle.org' => [
                        'fragment_size' => 200,
                        'number_of_fragments' => 1,
                    ],
                    'pagetitle.raw' => [
                        'fragment_size' => 200,
                        'number_of_fragments' => 1,
                    ],
                    'pagetitle.ng' => [
                        'fragment_size' => 200,
                        'number_of_fragments' => 1,
                    ],
                    'vendor_name' => [
                        'fragment_size' => 200,
                        'number_of_fragments' => 1,
                    ],
                ],
            ]);


        }

    }
}
