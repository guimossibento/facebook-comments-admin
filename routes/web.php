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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

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
