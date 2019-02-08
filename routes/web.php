<?php

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

Route::get('/','PagesController@root')->name('root');


// Authentication Routes...
//Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
//Route::post('login', 'Auth\LoginController@login');
//Route::post('logout', 'Auth\LoginController@logout')->name('logout');
//
//// Registration Routes...
//Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//Route::post('register', 'Auth\RegisterController@register');
//
//// Password Reset Routes...
//Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
//Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
//Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
//Route::post('password/reset', 'Auth\ResetPasswordController@reset');
//此处是 Laravel 的用户认证路由，以上等同于：
Auth::routes();



//Route::get('/users/{user}', 'UsersController@show')->name('users.show');
//Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');
//Route::patch('/users/{user}', 'UsersController@update')->name('users.update');
//上面代码将等同于：
Route::resource('users','UsersController',['only'=>['show','update','edit']]);


Route::resource('topics', 'TopicsController', ['only' => ['index','create', 'store', 'update', 'edit', 'destroy']]);

//URI 参数 topic 是 『隐性路由模型绑定』 的提示，将会自动注入 ID 对应的话题实体。
/*
 *URI 最后一个参数表达式 {slug?} ，? 意味着参数可选，这是为了兼容我们数据库中 Slug 为空的话题数据。这种写法可以同时兼容以下两种链接：
 * http://larabbs.test/topics/119
 * http://larabbs.test/topics/119/slug-translation-test
 * */
Route::get('topics/{topic}/{slug?}','TopicsController@show')->name('topics.show');


Route::resource('categories','CategoriesController',['only' => ['show']]);

Route::post('upload_image','TopicsController@uploadImage')->name('topics.upload_image');
Route::resource('replies', 'RepliesController', ['only' => ['index', 'show', 'create', 'store', 'update', 'edit', 'destroy']]);