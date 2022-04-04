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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  if (Auth::guest()) {
    return view('auth/login');
  }

  return redirect('/dashboard');
});

Auth::routes(['verify' => true]);

Route::get('email/verify', '\App\Interface\Http\Controllers\Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', '\App\Interface\Http\Controllers\Auth\VerificationController@verify')->name('verification.verify');
Route::post('email/resend', '\App\Interface\Http\Controllers\Auth\VerificationController@resend')->name('verification.resend');

Route::get('register', '\App\Interface\Http\Controllers\Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', '\App\Interface\Http\Controllers\Auth\RegisterController@register');

Route::get('password/reset', '\App\Interface\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', '\App\Interface\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', '\App\Interface\Http\Controllers\Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', '\App\Interface\Http\Controllers\Auth\ResetPasswordController@reset')->name('password.update');
Route::get('password/confirm', '\App\Interface\Http\Controllers\Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
Route::post('password/confirm', '\App\Interface\Http\Controllers\Auth\ConfirmPasswordController@confirm');

Route::get('/login', [\App\Interface\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Interface\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [\App\Interface\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('home', function () {
  return redirect('/dashboard');
});

Route::get('niches/list', [\App\Interface\Http\Controllers\Api\NicheController::class, 'list']);
Route::get('facebook-accounts/list', [\App\Interface\Http\Controllers\Api\FacebookAccountController::class, 'list']);
Route::get('comments/list', [\App\Interface\Http\Controllers\Api\CommentController::class, 'list']);

\BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter::webSocket('/broadcast/comment-log/app/{appKey}',
  \App\Interface\Http\Controllers\CommentLogSocketHandler::class);

Route::get('/{vue_capture?}', function () {
  return view('home');
})->where('vue_capture', '[\/\w\.-]*')->middleware('auth');
