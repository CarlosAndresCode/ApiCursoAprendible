<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\SaveArticleResquest;
use App\Models\Article;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticleCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request): ArticleCollection
    {
        $articles = Article::query();

        if ($request->filled('sort')) {

            $sortFields = explode(',', $request->input('sort'));

            $allowedSorts = ['title', 'content'];

            foreach ($sortFields as $sortField) {
                $sortDirection = Str::of($sortField)->startsWith('-') ? 'desc' : 'asc';

                $sortField = ltrim($sortField, '-');

                abort_unless(in_array($sortField, $allowedSorts), 400);

                $articles->orderBy($sortField, $sortDirection);
            }

        }

        return ArticleCollection::make($articles->get());
//        $sortField = $request->input('sort');
//        $sortDirection = Str::of($sortField)->startsWith('-') ? 'desc' : 'asc';
//        $sortField = ltrim($sortField, '-');
//        $articles = Article::orderBy($sortField, $sortDirection)->get();
//        return ArticleCollection::make($articles);
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
