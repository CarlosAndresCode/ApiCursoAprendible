<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_article()
    {
       $response = $this->postJson(route('api.v1.articles.store'), [
           'title' => 'title',
           'slug' => 'title',
           'content' => 'content',
           'active' => true
       ]);

       $response->assertCreated();

       $article = Article::first();

       $response->assertHeader(
           'location', route('api.v1.articles.show', $article)
       );

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
    public function title_is_required()
    {
        $response = $this->postJson(route('api.v1.articles.store'), [
            'slug' => 'slug',
            'content' => 'content',
            'active' => true
        ]);

        $response->assertJsonStructure([
        'errors' => [
                ['title', 'detail', 'source' => ['pointer']]
            ]
        ])->assertJsonFragment([
            'source' => ['pointer' => '/data/attributes/title']
        ])->assertStatus(422);
    }

    /** @test */
    public function slug_is_required()
    {
        $response = $this->postJson(route('api.v1.articles.store'), [
            'title' => 'title',
            'content' => 'content',
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
        $response = $this->postJson(route('api.v1.articles.store'), [
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
