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

Route::get('/', function () {
    return view('index');
});

Route::get('/about', function () {
    return view('about');
});

Route::resource('events', 'EventController');
Route::post('events/{event}/reserve', 'EventController@reserve')->name('events.reserve');
Route::post('events/{event}/cancel', 'EventController@cancel')->name('events.cancel');
Route::post('events/{event}/tickets', 'EventController@tickets')->name('events.tickets');

Route::get('uploads/events/images/{image}', function ($image) {
    return response()->file(storage_path('uploads/events/images/' . $image));
})->name('events.image');

Auth::routes();

Route::get('/dashboard', 'DashboardController@index')->name('home');

Route::group(['prefix' => 'admin'], function () {
    Route::get('/login', 'AdminAuth\LoginController@showLoginForm')->name('login.admin');
    Route::post('/login', 'AdminAuth\LoginController@login');
    Route::post('/logout', 'AdminAuth\LoginController@logout')->name('logout.admin');
    Route::get('/home', 'AdminController@index')->name('admin.home');

    Route::get('/register', 'AdminAuth\RegisterController@showRegistrationForm')->name('register.admin');
    Route::post('/register', 'AdminAuth\RegisterController@register');

    Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request.admin');
    Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset')->name('password.email.admin');
    Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset.admin');
    Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');
});

Route::group(['prefix' => 'eo'], function () {
    Route::get('/login', 'EoAuth\LoginController@showLoginForm')->name('login.eo');
    Route::post('/login', 'EoAuth\LoginController@login');
    Route::post('/logout', 'EoAuth\LoginController@logout')->name('logout.eo');

    Route::get('/register', 'EoAuth\RegisterController@showRegistrationForm')->name('register.eo');
    Route::post('/register', 'EoAuth\RegisterController@register');

    Route::post('/password/email', 'EoAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request.eo');
    Route::post('/password/reset', 'EoAuth\ResetPasswordController@reset')->name('password.email.eo');
    Route::get('/password/reset', 'EoAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset.eo');
    Route::get('/password/reset/{token}', 'EoAuth\ResetPasswordController@showResetForm');
});
