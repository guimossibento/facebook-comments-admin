<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Api routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your Api!
|
*/

Route::get('version', function () {
    return response()->json(['version' => config('app.version')]);
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    Log::debug('User:' . serialize($request->user()));
    return $request->user();
});


Route::namespace('App\\Http\\Controllers\\Api\V1')->group(function () {
    Route::get('profile', 'ProfileController@profile');
    Route::put('profile', 'ProfileController@updateProfile');
    Route::post('change-password', 'ProfileController@changePassword');

    Route::apiResources([
        'user' => 'UserController',
    ]);
});


Route::namespace('App\\Http\\Controllers\\Api')
    ->middleware('auth:api')
    ->group(function () {

    Route::put('dashboard/execute-comments', 'DashboardController@executeComments');
    Route::get('niches/list', [\App\Http\Controllers\Api\NicheController::class, 'list']);

    Route::put('comments/{comment}/niches', 'CommentNicheController@sync');
    Route::put('facebook-accounts/{facebookAccount}/niches', 'FacebookAccountNicheController@sync');


    Route::apiResources([
        'comments' => 'CommentController',
        'niches' => 'NicheController',
        'facebook-accounts' => 'FacebookAccountController',
        'comment-logs' => 'CommentLogController',
    ]);
});
