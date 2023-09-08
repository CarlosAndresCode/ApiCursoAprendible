<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class JsonApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Builder::macro('allowedSorts', function ($allowedSorts){

            if (request()->filled('sort')) {

                $sortFields = explode(',', request()->input('sort'));

                foreach ($sortFields as $sortField) {
                    $sortDirection = Str::of($sortField)->startsWith('-') ? 'desc' : 'asc';

                    $sortField = ltrim($sortField, '-');

                    abort_unless(in_array($sortField, $allowedSorts), 400);

                    $this->orderBy($sortField, $sortDirection);
                }
            }

            return $this;
        });

        Builder::macro('jsonPaginate', function (){
            return $this->paginate(
                $perPage = request('page.size', 15),
                $columns = ['*'],
                $pageName = 'page[number]',
                $page = request('page.number', 1)
            )->appends(request()->only('sort', 'filter', 'page.size'));
        });

        Builder::macro('jsonFilter', function (){
            $allowedFilters = ['title', 'content', 'year', 'month'];
            foreach (request('filter', []) as $column => $values) {
                abort_unless(in_array($column, $allowedFilters), 400);

//            if ($column === 'year'){
//                $articles->whereYear('created_at', $values);
//            }else if ($column === 'month'){
//                $articles->whereMonth('created_at', $values);
//            } else{
//                $articles->where($column, 'LIKE', '%'.$values.'%');
//            }
                $this->hasNamedScope($column)
                    ? $this->{$column}($values) // Se llama a los scope de manera dinamica
                    : $this->where($column, 'LIKE', '%'.$values.'%'); // No tiene scope en modelo ni title ni content
                // Son filtros por defecto title y content
            }
            return $this;
        });
    }
}
