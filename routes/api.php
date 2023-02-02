<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\MediaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('users/login',[UserController::class, 'login']);

Route::post('users/',[UserController::class, 'store']);

Route::get('posts/', [PostController::class, 'index']);

Route::get('posts/{post}', [PostController::class, 'show']);

Route::post('videos/',[VideoController::class, 'store']);

Route::get('/login',function() {
    return response()->json([
            'message'=>'login is not allowed'
        ]);
})->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('me')->controller(MeController::class)->group(function () {
        Route::get('/', 'index');
    });

    Route::prefix('users')->controller(UserController::class)->group(function () {
        //Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
        //Route::get('/', 'index');
    });

    Route::prefix('posts')->controller(PostController::class)->group(function () {
        Route::post('/', 'store');
        //Route::get('/{post}', 'show');
        Route::put('/{post}', 'update');
        Route::delete('/{post}', 'destroy');
        //Route::get('/', 'index');
    });

    Route::prefix('contacts')->controller(ContactController::class)->group(function () {
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
        Route::get('/', 'index');
    });
});
