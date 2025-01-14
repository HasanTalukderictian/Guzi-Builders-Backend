<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ControllerBlog;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TestominalController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashController;



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


Route::post('/students', [StudentController::class, 'store']);

Route::post('/blogs', [ControllerBlog::class, 'store']);
Route::get('/get-blogs', [ControllerBlog::class, 'index']);

Route::delete('/delete-blog/{id}', [ControllerBlog::class, 'destroy']);

Route::post('/edit-blog/{id}', [ControllerBlog::class, 'update']);


// About

Route::post('/add-about', [AboutController::class, 'store']);
Route::get('/about', [AboutController::class, 'index']);
Route::delete('/del-about/{id}', [AboutController::class, 'destroy']);
Route::post('/edit-about/{id}', [AboutController::class, 'update']);


//service
Route::post('/add-service', [ServiceController::class, 'store']);
Route::get('/get-service', [ServiceController::class, 'index']);
Route::delete('/del-service/{id}', [ServiceController::class, 'destroy']);
Route::post('/edit-service/{id}', [ServiceController::class, 'update']);



//projects
Route::post('/add-projects', [ProjectController::class, 'store']);
Route::get('/get-projects', [ProjectController::class, 'index']);
Route::get('/get-projects/{id}', [ProjectController::class, 'show']);
Route::delete('/del-projects/{id}', [ProjectController::class, 'destroy']);
Route::post('/edit-projects/{id}', [ProjectController::class, 'update']);


//testominal
Route::post('/add-testominal', [TestominalController::class, 'store']);
Route::get('/get-testominal', [TestominalController::class, 'index']);
Route::delete('/del-testominal/{id}', [TestominalController::class, 'destroy']);
Route::post('/edit-testominal/{id}', [TestominalController::class, 'update']);


//team
Route::post('/add-team', [TeamController::class, 'store']);
Route::get('/get-team', [TeamController::class, 'index']);
Route::delete('/del-team/{id}', [TeamController::class, 'destroy']);
Route::post('/edit-team/{id}', [TeamController::class, 'update']);



Route::post('/contact', [ContactController::class, 'store']);

Route::get('/students', [StudentController::class, 'hello']);



Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/logout', [AdminAuthController::class, 'logout']);
    Route::middleware('auth:sanctum')->get('/admin-all', [AdminDashController::class, 'index']);
});
