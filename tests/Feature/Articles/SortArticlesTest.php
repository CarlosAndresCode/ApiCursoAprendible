<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SortArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_sort_articles_by_title()
    {
       $article = Article::factory()->create(['title' => 'B title']);
       $article = Article::factory()->create(['title' => 'C title']);
       $article = Article::factory()->create(['title' => 'A title']);

       //articles?sort=title
        $url = route('api.v1.articles.index', ['sort' => 'title']);

        $this->getJson($url)->assertSeeInOrder([
            'A title',
            'B title',
            'C title',
        ]);
    }

    /** @test */
    public function can_sort_articles_by_title_descending()
    {
        $article = Article::factory()->create(['title' => 'B title']);
        $article = Article::factory()->create(['title' => 'C title']);
        $article = Article::factory()->create(['title' => 'A title']);

        //articles?sort=-title // se agrega el menos en el atributo a ordenar para que lo haga desc
        $url = route('api.v1.articles.index', ['sort' => '-title']);

        $this->getJson($url)->assertSeeInOrder([
            'C title',
            'B title',
            'A title',
        ]);
    }
}
