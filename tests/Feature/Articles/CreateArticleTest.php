<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_article(): void
    {
       $response = $this->postJson(route('api.v1.articles.create'), [
           'data' => [
               'type' => 'articles',
               'attributes' => [
                   'title' => 'titulo',
                   'slug' => 'title',
                   'content' => 'contenido',
                   'active' => true
               ]
           ]
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
                   'content' => $article->content
               ],
               'links' => [
                   'self' => route('api.v1.articles.show', $article)
               ],
           ]
       ]);
    }


    /** @test */
    public function title_is_required(){
        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'slug' => 'title',
                    'content' => 'contenido',
                    'active' => true
                ]
            ]
        ]);
        $response->assertJsonValidationErrors('data.attributes.title');
    }

    /** @test */
    public function slug_is_required(){
        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'title',
                    'content' => 'contenido',
                    'active' => true
                ]
            ]
        ]);
        $response->assertJsonValidationErrors('data.attributes.slug');
    }

    /** @test */
    public function content_is_required(){
        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'title',
                    'slug' => 'slug',
                    'active' => true
                ]
            ]
        ]);
        $response->assertJsonValidationErrors('data.attributes.content');
    }

}
