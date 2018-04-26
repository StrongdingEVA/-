<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => 'auth'],function(){
    Route::get('auth/logout', 'Auth\AuthController@getLogout');//登出动作
    Route::post('uploadimg', 'ArticleController@uploadimg');//插件上传图片
    Route::post('createarticle', 'ArticleController@createArticle');//发布文章动作
    Route::get('articles', 'ArticleController@article'); //发布文章的页面
    Route::post('auth/authcomment', 'Auth\AuthController@userComment'); //文章评论
    Route::post('auth/authanswer', 'Auth\AuthController@userAnswer'); //文章回复
    Route::get('auth/foucsusercancle/{id}', 'Auth\AuthController@foucsUserCancle'); //关注用户
    Route::get('picturewall', 'PictureWallController@index'); //照片墙
    Route::get('createpicwall', 'PictureWallController@getCreate'); //照片墙发布界面
    Route::post('createpicwall', 'PictureWallController@postCreate'); //照片墙
    Route::post('uploadimgwall', 'PictureWallController@uploadimgWall'); //照片墙

    Route::get('/sendMsg/{uid}', 'Auth\AuthController@sendMsgGet'); //发送私信

	Route::post('/doSendMsg', 'Auth\AuthController@sendMsgPost'); //发送私信

    // Route::get('/sendMsg',function(){
    //     \Illuminate\Support\Facades\Event::fire(new \App\Events\SomeEvent(1));
    //     return 'Hello World';
    // });
});

Route::get('/', 'ArticleController@index');
Route::get('homes', 'ArticleController@index');

Route::get('auth/login', 'Auth\AuthController@getLogin');//登录界面
Route::post('auth/login', 'Auth\AuthController@postLogin');//登录动作

Route::get('auth/register', ',Auth\AuthController@getRegister');//注册页面
Route::post('auth/register', 'Auth\AuthController@postRegister');//注册动作
Route::post('auth/getchecknum', 'Auth\AuthController@getCheckNumber');//验证码

Route::get('article_detail/{id}', 'ArticleController@detail');//文章详情

Route::get('article_detail_2/{id}/{comval}/{comtype}/{c_id}', 'ArticleController@detail_2');//文章详情2

Route::get('categoryarticle/{id}', 'ArticleController@categoryArticle');//获取分类文章

Route::post('auth/colletion', 'Auth\AuthController@userColletion'); //文章点赞

Route::post('auth/colletioncancel', 'Auth\AuthController@userColletionCancel'); //文章取消点赞

Route::get('auth/foucsuser/{id}', 'Auth\AuthController@foucsUser'); //关注用户

Route::get('getPics', 'PicController@getPics'); //关注用户

Route::get('picturewall/{type}', 'PictureWallController@index'); //照片墙
Route::get('picturewall_ajax/{page}/{order}', 'PictureWallController@picAjax'); //照片墙

Route::get('dolike/{id}/{type}', 'PictureWallController@doLikeAction'); //照片墙

Route::get('getFilePics', 'ArticleController@getFilePics'); //关注用户

Route::get('getbaidunews', 'ArticleController@getBaiDuNews');//获取分类文章

Route::get('getusermessage', 'UserMessageController@getUserMessage');//获取分类文章

Route::get('getansajax/{id}/{page}', 'AnswerController@getAnswerAjax');//获取评论的回复Ajax

Route::get('linshi/', 'ArticleController@linshi');//获取评论的回复Ajax

Route::get('test', 'ArticleController@test');


