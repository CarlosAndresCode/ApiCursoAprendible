<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_delete_article()
    {
       $article = Article::factory()->create();

       $response = $this->deleteJson(route('api.v1.articles.destroy', $article));

       $response->assertNoContent();
    }
}
