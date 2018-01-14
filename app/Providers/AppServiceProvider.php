<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->addCustomPagination();
    }


    private function addCustomPagination()
    {
        Builder::macro("customPagination", function ($perPage = null, $columns = ['*'], $pageName = 'page', $page = null) {
            $page = $page ?: Paginator::resolveCurrentPage($pageName);
            $perPage = $perPage ?: $this->model->getPerPage();
            $this->skip(($page - 1) * $perPage)->take($perPage + 1);
            $total = DB::select(DB::RAW('SELECT FOUND_ROWS() as total'))[0]->total;
            return $this->paginator($this->get($columns), $total, $perPage, $page, [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]);
        });
    }
}
