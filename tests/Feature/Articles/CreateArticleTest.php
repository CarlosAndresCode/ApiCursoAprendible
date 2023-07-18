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
}
