<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_fetch_a_single_article()
    {
        $this->withExceptionHandling();

        $article = Article::factory()->create();

        $response = $this->getJson(route('api.v1.articles.show', $article));

        $response->assertExactJson([
            'data' =>[
                'type' => 'articles',
                'id' => (string) $article->getRouteKey(),
                'attributes' => [
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'content' => $article->content,
                    'active' => $article->active
                ],
                'links' => [
                    'self' => route('api.v1.articles.show', $article)
                ],
            ]
        ]);
    }

    /** @test */
    public function can_fetch_all_articles()
    {
        $this->withExceptionHandling();

        $articles = Article::factory()->count(2)->create();

        $response = $this->getJson(route('api.v1.articles.index'));

        $response->assertJson([
            'data' => [
                [
                    'type' => 'articles',
                    'id' => (string) $articles[0]->getRouteKey(),
                    'attributes' => [
                        'title' => $articles[0]->title,
                        'slug' => $articles[0]->slug,
                        'content' => $articles[0]->content,
                        'active' => $articles[0]->active
                    ],
                    'links' => [
                        'self' => route('api.v1.articles.show', $articles[0]->getRouteKey())
                    ]
                ],
                [
                    'type' => 'articles',
                    'id' => (string) $articles[1]->getRouteKey(),
                    'attributes' => [
                        'title' => $articles[1]->title,
                        'slug' => $articles[1]->slug,
                        'content' => $articles[1]->content,
                        'active' => $articles[1]->active
                    ],
                    'links' => [
                        'self' => route('api.v1.articles.show', $articles[1]->getRouteKey())
                    ]
                ]
            ],
            'links' => [
                'self'=>route('api.v1.articles.index')
            ]
        ]);
    }
}
