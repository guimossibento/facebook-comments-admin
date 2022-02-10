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


Route::namespace('App\\Interface\\Http\\Controllers\\Auth')->group(function () {
	Route::get('profile', 'ProfileController@profile');
	Route::put('profile', 'ProfileController@updateProfile');
	Route::post('change-password', 'ProfileController@changePassword');
	
	Route::apiResources([
		'user' => 'UserController',
	]);
});


Route::namespace('App\\Interface\\Http\\Controllers\\Api')
	->middleware('auth:api')
	->group(function () {
		Route::post('facebook-accounts/test-login', 'FacebookAccountController@testLogin');
		
		Route::put('dashboard/execute-comments', 'DashboardController@executeComments');
		Route::get('niches/list', [\App\Interface\Http\Controllers\Api\NicheController::class, 'list']);
		
		Route::put('facebook-accounts/{facebookAccount}/niches', 'FacebookAccountNicheController@sync');
		
		Route::put('niches/{niche}/facebook-accounts', 'NicheFacebookAccountController@sync');
		
		Route::get('comment-logs', [\App\Interface\Http\Controllers\Api\CommentLogController::class, 'index']);
		Route::post('comment-logs', [\App\Interface\Http\Controllers\Api\CommentLogController::class, 'store']);
		Route::get('comment-logs/{commentLog}', [\App\Interface\Http\Controllers\Api\CommentLogController::class, 'show']);
		Route::delete('comment-logs/delete/all', [\App\Interface\Http\Controllers\Api\CommentLogController::class, 'destroyAll']);
		Route::delete('comment-logs/{commentLog}', [\App\Interface\Http\Controllers\Api\CommentLogController::class, 'destroy']);
		
		Route::get('comment-request-logs', [\App\Interface\Http\Controllers\Api\CommentRequestLogController::class, 'index']);
		Route::post('comment-request-logs', [\App\Interface\Http\Controllers\Api\CommentRequestLogController::class, 'store']);
		Route::get('comment-request-logs/{commentRequestLog}', [\App\Interface\Http\Controllers\Api\CommentRequestLogController::class, 'show']);
		Route::delete('comment-request-logs/delete/all', [\App\Interface\Http\Controllers\Api\CommentRequestLogController::class, 'destroyAll']);
		Route::delete('comment-request-logs/{commentRequestLog}', [\App\Interface\Http\Controllers\Api\CommentRequestLogController::class, 'destroy']);
		
		Route::apiResources([
			'comments' => 'CommentController',
			'niches' => 'NicheController',
			'facebook-accounts' => 'FacebookAccountController',
		]);
	});


Route::namespace('App\\Interface\\Http\\Controllers\\Api')
	->middleware('api')
	->group(function () {
		Route::post('comment-logs', [\App\Interface\Http\Controllers\Api\CommentLogController::class, 'store']);
	});
