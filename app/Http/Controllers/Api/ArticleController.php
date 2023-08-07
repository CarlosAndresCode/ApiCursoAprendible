<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticleCollection;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(): ArticleCollection
    {
        return ArticleCollection::make(Article::all());
    }

    public function show(Article $article): ArticleResource
    {
        return ArticleResource::make($article);
    }

    public function store(Request $request): ArticleResource
    {
        $request->validate([
            'title' => ['required'],
            'slug' => ['required'],
            'content' => ['required'],
        ]);

        $article = Article::create([
            'title' => $request->input('title'),
            'slug' => $request->input('slug'),
            'content' => $request->input('content'),
            'active' => $request->input('active')
            ]
        );

        return ArticleResource::make($article);
    }

    public function update(Article $article, Request $request) : ArticleResource
    {
        $request->validate([
            'title' => ['required'],
            'slug' => ['required'],
            'content' => ['required'],
        ]);

         $article->update([
             'title'=>$request->input('title'),
             'slug'=>$request->input('slug'),
             'content'=>$request->input('content'),
             'active'=>$request->input('active')
         ]);

         return ArticleResource::make($article);
    }
}
