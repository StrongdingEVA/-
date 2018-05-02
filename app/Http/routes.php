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
    Route::get('publish', 'ArticleController@article'); //发布文章的页面
    Route::post('auth/authcomment', 'Auth\AuthController@userComment'); //文章评论
    Route::post('answer', 'Auth\AuthController@userAnswer'); //文章回复
    Route::get('auth/foucsusercancle/{id}', 'Auth\AuthController@foucsUserCancle'); //关注用户
    Route::get('picturewall', 'PictureWallController@index'); //照片墙
    Route::get('createpicwall', 'PictureWallController@getCreate'); //照片墙发布界面
    Route::post('createpicwall', 'PictureWallController@postCreate'); //照片墙
    Route::post('uploadimgwall', 'PictureWallController@uploadimgWall'); //照片墙

    Route::get('/sendMsg/{uid}', 'Auth\AuthController@sendMsgGet'); //发送私信

	Route::post('/doSendMsg', 'Auth\AuthController@sendMsgPost'); //发送私信
});

Route::get('/blog/{key?}/{order?}/{search?}', 'ArticleController@index');
Route::get('', 'ArticleController@index');

Route::get('article_detail/{id}/{cid?}/{aid?}/{type?}', 'ArticleController@detail');//文章详情

Route::post('auth/colletion', 'Auth\AuthController@userColletion'); //文章点赞

Route::post('auth/colletioncancel', 'Auth\AuthController@userColletionCancel'); //文章取消点赞

Route::get('auth/foucsuser/{id}', 'Auth\AuthController@foucsUser'); //关注用户

Route::get('getPics', 'PicController@getPics'); //关注用户

Route::get('picturewall/{type}', 'PictureWallController@index'); //照片墙
Route::get('picturewall_ajax/{page}/{order}', 'PictureWallController@picAjax'); //照片墙

Route::get('dolike/{id}/{type}', 'PictureWallController@doLikeAction'); //照片墙

Route::get('getFilePics', 'ArticleController@getFilePics'); //关注用户

Route::get('getbaidunews', 'ArticleController@getBaiDuNews');//获取分类文章

Route::get('getmsg', 'UserMessageController@getUserMessage');//获取分类文章

Route::get('getansajax/{id}/{page}', 'AnswerController@getAnswerAjax');//获取评论的回复Ajax

Route::get('linshi/', 'ArticleController@linshi');//获取评论的回复Ajax

Route::get('test', 'ArticleController@test');
Route::get('test_server', 'ArticleController@test_server');
Route::get('test_client', 'ArticleController@test_client');


Route::get('auth/login', 'Auth\AuthController@getLogin');//登录界面
Route::post('auth/login', 'Auth\AuthController@postLogin');//登录动作

Route::get('auth/register', ',Auth\AuthController@getRegister');//注册页面
Route::post('auth/register', 'Auth\AuthController@postRegister');//注册动作
Route::post('auth/getchecknum', 'Auth\AuthController@getCheckNumber');//验证码


Route::post('checkIsLogin', 'Auth\AuthController@checkIsLogin');//验证登录状态





