<?php

namespace App\Providers;

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
        $userInfo = Auth::user();
        $key = 'world';
        $search = '';
        view()->share('userInfo',$userInfo);
        view()->share('search',$search);
        view()->share('key',$key);
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
