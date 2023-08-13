<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\SaveArticleResquest;
use App\Models\Article;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticleCollection;
use Illuminate\Http\Response;

class ArticleController extends Controller
{
    public function index(): ArticleCollection
    {
        // allowedSort es un metodo que esta en macro en provider JsonApiServiceProvider.php
        // el cual usa macro de la clase Builder de Eloquent
        // JsonApiServiceProvider.php se registra en los providers en el archivo config/app.php
        $articles = Article::allowedSorts(['title', 'content']);

        return ArticleCollection::make($articles->get());

    }
    public function show(Article $article): ArticleResource
    {
        return ArticleResource::make($article);
    }
    public function store(SaveArticleResquest $request): ArticleResource
    {
        $article = Article::create([
            'title' => $request->input('title'),
            'slug' => $request->input('slug'),
            'content' => $request->input('content'),
            'active' => $request->input('active')
            ]
        );

        return ArticleResource::make($article);
    }
    public function update(Article $article, SaveArticleResquest $request) : ArticleResource
    {
        $article->update([
             'title'=>$request->input('title'),
             'slug'=>$request->input('slug'),
             'content'=>$request->input('content'),
             'active'=>$request->input('active')
         ]);

         return ArticleResource::make($article);
    }
    public function destroy(Article $article) : Response
    {
        $article->delete();

        return response()->noContent();
    }
}
