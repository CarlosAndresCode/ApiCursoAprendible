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

    /** @test  */
    public function title_is_required()
    {
        $this->withExceptionHandling();

        $article = Article::factory()->create();

        $response = $this->patchJson(route('api.v1.articles.update', $article),[
            'slug' => 'slug-update',
            'content' => 'content update',
            'active' => true
        ])->dump();

        $response->assertJsonStructure([
            'errors' => [
                ['title', 'detail', 'source' => ['pointer']]
            ]
        ]);

        $response->assertJsonFragment([
            'source' => ['pointer' => '/data/attributes/title']
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function slug_is_required()
    {
        $this->withExceptionHandling();

        $article = Article::factory()->create();

        $response = $this->patchJson(route('api.v1.articles.update', $article),[
            'title' => 'title update',
            'content' => 'content update',
            'active' => true
        ]);
        $response->assertJsonStructure([
            'errors' => [
                ['title', 'detail', 'source' => ['pointer']]
            ]
        ])->assertJsonFragment([
            'source' => ['pointer' => '/data/attributes/slug']
        ])->assertStatus(422);
    }

    /** @test */
    public function content_is_required()
    {
        $this->withExceptionHandling();

        $article = Article::factory()->create();

        $response = $this->patchJson(route('api.v1.articles.update', $article),[
            'title' => 'title',
            'slug' => 'slug',
            'active' => true
        ]);
        $response->assertJsonStructure([
            'errors' => [
                ['title', 'detail', 'source' => ['pointer']]
            ]
        ])->assertJsonFragment([
            'source' => ['pointer' => '/data/attributes/content']
        ])->assertStatus(422);
    }
}
