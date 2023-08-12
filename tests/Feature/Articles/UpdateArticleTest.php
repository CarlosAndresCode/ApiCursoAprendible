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
            'slug' => $article->slug,
            'content' => 'content update',
            'active' => true
        ];

        $response = $this->putJson(route('api.v1.articles.update', $article),$data);

        $response->assertOk();

        $response->assertHeader('location', route('api.v1.articles.show', $article));

        $response->assertExactJson([
            'data' =>[
                'type' => 'articles',
                'id' => (string) $article->getRouteKey(),
                'attributes' => [
                    'title' => 'title update',
                    'slug' => $article->slug,
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

        $response = $this->putJson(route('api.v1.articles.update', $article),[
            'slug' => 'slug-update',
            'content' => 'content update',
            'active' => true
        ]);

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

        $response = $this->putJson(route('api.v1.articles.update', $article),[
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
    public function slug_must_be_unique()
    {
        $article1 = Article::factory()->create();

        $article2 = Article::factory()->create();

        $response = $this->putJson(route('api.v1.articles.update', $article1), [
            'title' => 'title',
            'slug' => $article2->slug,
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

    public function slug_must_only_contain_letters_numbers_and_dashes()
    {
        $article = Article::factory()->create();

        $response = $this->putJson(route('api.v1.articles.update', $article), [
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
        $article = Article::factory()->create();

        $response = $this->putJson(route('api.v1.articles.update', $article), [
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
        $article = Article::factory()->create();

        $response = $this->putJson(route('api.v1.articles.update', $article), [
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
        $article = Article::factory()->create();

        $response = $this->putJson(route('api.v1.articles.update', $article), [
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
        $this->withExceptionHandling();

        $article = Article::factory()->create();

        $response = $this->putJson(route('api.v1.articles.update', $article),[
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
