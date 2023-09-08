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
        $articles = Article::query();

//        dd(request('filter'));
        //Filter
        $allowedFilters = ['title', 'content', 'year', 'month'];
        foreach (request('filter', []) as $column => $values) {
            abort_unless(in_array($column, $allowedFilters), 400);
            if ($column === 'year'){
                $articles->whereYear('created_at', $values);
            }else if ($column === 'month'){
                $articles->whereMonth('created_at', $values);
            } else{
                $articles->where($column, 'LIKE', '%'.$values.'%');
            }
        }


        // allowedSort es un metodo que esta en macro en provider JsonApiServiceProvider.php
        // el cual usa macro de la clase Builder de Eloquent
        // JsonApiServiceProvider.php se registra en los providers en el archivo config/app.php
        $articles->allowedSorts(['title', 'content']);

        // jsonPaginate es un metodo que esta en macro en provider JsonApiServiceProvider.php
        // el cual se encarga de tener el codigo para la paginacion
        return ArticleCollection::make($articles->jsonPaginate());

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
