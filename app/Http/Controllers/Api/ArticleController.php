<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    public function show(Article $article)
    {
        return response()->json(
            [
                'data' =>[
                    'type' => 'articles',
                    'id' => (string) $article->getRouteKey(),
                    'attributes' => [
                        'title' => $article->title,
                        'slug' => $article->slug,
                        'content' => $article->content
                    ],
                    'links' => [
                        'self' => route('articles.show', $article)
                    ],
                ]
            ]
        );
    }
}
