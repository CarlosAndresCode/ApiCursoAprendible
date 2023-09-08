<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function Symfony\Component\String\u;

class FilterArticlesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_filter_articles_by_title()
    {
       Article::factory()->create([
           'title' => 'Aprende Laravel Desde Cero'
       ]);
        Article::factory()->create([
            'title' => 'Otro titulo'
        ]);

        //api/articles?filter[title]=Laravel //Segun JsonApi

        $url = route('api.v1.articles.index', [
            'filter' => [
                'title' => 'Laravel'
            ]
        ]);

//       dd(urldecode($url));

       $this->getJson($url)
           ->assertJsonCount(1, 'data')
            ->assertSee('Aprende Laravel Desde Cero')
            ->assertDontSee('Otro titulo');
    }

    /** @test */
    public function can_filter_articles_by_content()
    {
        Article::factory()->create([
            'content' => 'Laravel es el mejor'
        ]);
        Article::factory()->create([
            'content' => 'Otro contenido'
        ]);

        //api/articles?filter[content]=Laravel //Segun JsonApi

        $url = route('api.v1.articles.index', [
            'filter' => [
                'content' => 'Laravel'
            ]
        ]);
        $this->getJson($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('Laravel es el mejor')
            ->assertDontSee('Otro contenido')
        ;

    }

    /** @test */
    public function can_filter_articles_by_year()
    {
        Article::factory()->create([
            'title' => 'articles from 2020 year',
            'created_at' => now()->year(2020)
        ]);
        Article::factory()->create([
            'title' => 'articles from 2021 year',
            'created_at' => now()->year(2021)
        ]);

        //api/articles?filter[year]=Laravel //Segun JsonApi

        $url = route('api.v1.articles.index', [
            'filter' => [
                'year' => '2020'
            ]
        ]);
        $this->getJson($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('articles from 2020 year')
            ->assertDontSee('articles from 2021 year')
        ;

    }

    /** @test */
    public function can_filter_articles_by_month()
    {
        Article::factory()->create([
            'title' => 'articles from 2 month',
            'created_at' => now()->month(2)
        ]);
        Article::factory()->create([
            'title' => 'articles from 1 month',
            'created_at' => now()->month(1)
        ]);

        //api/articles?filter[month]=Laravel //Segun JsonApi

        $url = route('api.v1.articles.index', [
            'filter' => [
                'month' => '2'
            ]
        ]);
        $this->getJson($url)
            ->assertJsonCount(1, 'data')
            ->assertSee('articles from 2 month')
            ->assertDontSee('articles from 1 month')
        ;

    }

    /** @test */
    public function cannot_filter_articles_by_unknown_filter()
    {
        Article::factory()->create([
            'title' => 'articles from 2 month',
            'created_at' => now()->month(2)
        ]);
        Article::factory()->create([
            'title' => 'articles from 1 month',
            'created_at' => now()->month(1)
        ]);

        //api/articles?filter[unknown]=Laravel //Segun JsonApi

        $url = route('api.v1.articles.index', [
            'filter' => [
                'unknown' => '2'
            ]
        ]);
        $this->getJson($url)
            ->assertStatus(400);
        ;

    }
}
