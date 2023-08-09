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
    public function slug_must_be_required()
    {
        $article = Article::factory()->create();

        $response = $this->postJson(route('api.v1.articles.store'), [
            'title' => 'title',
            'slug' => $article->slug,
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
    public function slug_must_be_unique()
    {
        $article = Article::factory()->create();

        $response = $this->postJson(route('api.v1.articles.store'), [
            'title' => 'title',
            'slug' => $article->slug,
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
    public function slug_must_only_contain_letters_numbers_and_dashes()
    {
        $response = $this->postJson(route('api.v1.articles.store'), [
            'title' => 'title',
            'slug' => '$-',
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
    public function slug_must_not_contain_underscored()
    {
        $response = $this->postJson(route('api.v1.articles.store'), [
            'title' => 'title',
            'slug' => 'with_underscored',
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
    public function slug_must_not_start_with_dash()
    {
        $response = $this->postJson(route('api.v1.articles.store'), [
            'title' => 'title',
            'slug' => '-start-dash',
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
    public function slug_must_not_end_with_dash()
    {
        $response = $this->postJson(route('api.v1.articles.store'), [
            'title' => 'title',
            'slug' => 'end-dash-',
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
