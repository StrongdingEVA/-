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
        //
        //查询分类
        $actionLi = 99;
        view()->share('actionLi',$actionLi);
        //查询分类
        $cateInfo = Cache::remember('cateInfo', 3600, function() {
            return Category::where("level",0)->orderBy("id","asc")->get();
        });
        foreach($cateInfo as $k => $v){
            $cateInfo[$k]["article"] = ArticleController::getDaily($v["id"]);
        }
        //视图间共享数据
        view()->share('cateInfo',$cateInfo);
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
