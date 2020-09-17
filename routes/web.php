<?php

use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', 'StaticPagesController@home')->name('home');
Route::get('/help', 'StaticPagesController@help')->name('help');
Route::get('/about', 'StaticPagesController@about')->name('about');

Route::get('signup', 'UsersController@create')->name('signup');

Route::resource('users', 'UsersController');

Route::get('login', 'SessionsController@create')->name('login');
Route::post('login', 'SessionsController@store')->name('login');
Route::delete('logout', 'SessionsController@logout')->name('logout');

//Route::get('show/{id}/edit','UsersController@edit')->name('users.edit');

Route::get('signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');

// 显示重置密码的邮箱发送页面
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// 重置邮箱链接
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
//密码更新页面
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// 执行密码更新操作
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// 这一块是删除微博和发布微博的路由
Route::resource('statuses', 'StatusesController', ['only' => ['store', 'destroy']]);
//显示用户的关注人列表
Route::get('/users/{user}/followings', 'UsersController@followings')->name('users.followings');
//显示用户的粉丝列表
Route::get('/users/{user}/followers', 'UsersController@followers')->name('users.followers');

// 关注
Route::post('/users/followers/{user}', 'FollowersController@store')->name('followers.store');
// 取关
Route::delete('/users/followers/{user}', 'FollowersController@destroy')->name('followers.destroy');

// 测试图片路由
//Route::get('img/ajax', '')
//Route::get('img/test', function () {
//    //自定义验证码长度，和内容范围
//    $phraseBuilder = new PhraseBuilder(4);
//    $builder = new CaptchaBuilder(null, $phraseBuilder); //参数传给cap构造类
//    $captcha = $builder->build();   //生成图片验证码
//    $captcha_content = $captcha->getPhrase();   // 获取图片验证码中的内容
//    // 将数据存储到缓存中，时间为2分钟
//    Cache::put('captcha_content', $captcha_content, 2);
//    // 从缓存中取出
////        $captcha_cache_content = Cache::get('captcha');
////        dd($captcha_cache_content);
//    $captcha->save('out.jpg');
//    $captcha_base64_content = $captcha->inline();  // 转化成base64
////    return [$captcha_content, $captcha_cache_content];
////    info($captcha_base64_content);  //写入log
//    return [
//        'img' => $captcha_base64_content,
//    ];
//})->name('getImg');

Route::get('img/captcha', 'SessionsController@getCaptcha')->name('getImg');
