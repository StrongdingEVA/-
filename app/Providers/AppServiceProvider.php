<?php

namespace App\Providers;

use App\Article;
use App\Category;
use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
        $key = 'world';
        $search = '';
        $scrollArticle = Article::getSrollArticle();
        view()->share('search',$search);
        view()->share('key',$key);
        view()->share('scrollArticle',$scrollArticle);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
