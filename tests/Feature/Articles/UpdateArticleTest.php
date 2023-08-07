<?php

namespace Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_update_article()
    {
        $this->withExceptionHandling();

        $article = Article::factory()->create();

        $data = [
            'title' => 'title update',
            'slug' => 'title-update',
            'content' => 'content update',
            'active' => true
        ];

        $response = $this->patchJson(route('api.v1.articles.update', $article),$data);

        $response->assertOk();

        $response->assertHeader('location', route('api.v1.articles.show', $article));

        $response->assertExactJson([
            'data' =>[
                'type' => 'articles',
                'id' => (string) $article->getRouteKey(),
                'attributes' => [
                    'title' => 'title update',
                    'slug' => 'title-update',
                    'content' => 'content update',
                    'active' => true
                ],
                'links' => [
                    'self' => route('api.v1.articles.show', $article)
                ],
            ]
        ]);
    }
}
