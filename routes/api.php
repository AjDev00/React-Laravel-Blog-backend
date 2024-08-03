<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\TempImageContoller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//inserting blogs api(localhost:800/api/blogs).
Route::post('blogs', [BlogController::class, 'store']);

//inserting images api
Route::post('save-temp-image', [TempImageContoller::class,'store']);

//getting all blogs.
Route::get('blogs', [BlogController::class, 'index']);

//get first blog.
Route::get('first-blog', [BlogController::class, 'showFirst']);

//get a specific blog.
Route::get('blogs/{id}', [BlogController::class, 'show']);