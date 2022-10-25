<?php

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

// Authentication route
Route::post('/register', [App\Http\Controllers\Auth\AuthController::class, 'register'])->name('register');
Route::post('/login', [App\Http\Controllers\Auth\AuthController::class, 'login'])->name('login');

Route::group(['middleware' => ['web']], function () {
    Route::get('/auth/redirect/{driver}', [App\Http\Controllers\Auth\SocialiteController::class, 'socialiteRedirect'])->name('socialite.redirect');
    Route::get('/auth/callback/{driver}', [App\Http\Controllers\Auth\SocialiteController::class, 'socialiteCallback'])->name('socialite.callback');
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    // Logout route
    Route::post('/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');

    Route::group(['as' => 'admin.'], function () {
        // Role routes
        Route::get('/roles', [\App\Http\Controllers\Auth\RoleController::class, 'allRoles'])->name('role.all');
        Route::get('/permissions', [\App\Http\Controllers\Auth\RoleController::class, 'allPermission'])->name('permission.all');
        Route::post('/role/create', [\App\Http\Controllers\Auth\RoleController::class, 'createRole'])->name('role.create');
        Route::get('/role/{id}', [\App\Http\Controllers\Auth\RoleController::class, 'getRole'])->name('role.get');
        Route::put('/role/update/{id}', [\App\Http\Controllers\Auth\RoleController::class, 'updateRole'])->name('role.update');
        Route::post('/role/destroy/{id}', [\App\Http\Controllers\Auth\RoleController::class, 'destroy'])->name('role.destroy');

        // Category routes
        Route::group(['prefix' => 'category'], function () {
            Route::get('/', [App\Http\Controllers\CategoryController::class, 'index'])->name('category.list');
            Route::post('/create', [App\Http\Controllers\CategoryController::class, 'createCategory'])->name('category.create');
            Route::get('/{category}', [App\Http\Controllers\CategoryController::class, 'getCategory'])->name('category.get');
            Route::put('/update/{category}', [App\Http\Controllers\CategoryController::class, 'updateCategory'])->name('category.update');
            Route::post('/destroy/{category}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('category.destroy');
        });

        // Course routes
        Route::group(['prefix' => 'course'], function () {
            Route::get('/', [App\Http\Controllers\CourseController::class, 'index'])->name('course.list');
            Route::post('/create', [App\Http\Controllers\CourseController::class, 'createCourse'])->name('course.create');
            Route::get('/{course}', [App\Http\Controllers\CourseController::class, 'getCourse'])->name('course.get');
            Route::post('approve/{course}', [App\Http\Controllers\CourseController::class, 'approved'])->name('course.approve');
            Route::put('/update/{course}', [App\Http\Controllers\CourseController::class, 'updateCourse'])->name('course.update');
            Route::delete('/destroy/{course}', [App\Http\Controllers\CourseController::class, 'destroy'])->name('course.destroy');
        });

        // Batch routes
        Route::group(['prefix' => 'batch'], function () {
            Route::get('/', [App\Http\Controllers\BatchController::class, 'index'])->name('batch.list');
            Route::post('/create', [App\Http\Controllers\BatchController::class, 'createBatch'])->name('batch.create');
            Route::get('/{batch}', [App\Http\Controllers\BatchController::class, 'getBatch'])->name('batch.get');
            Route::post('approve/{batch}', [App\Http\Controllers\BatchController::class, 'approved'])->name('batch.approve');
            Route::put('/update/{batch}', [App\Http\Controllers\BatchController::class, 'updateBatch'])->name('batch.update');
            Route::delete('/destroy/{batch}', [App\Http\Controllers\BatchController::class, 'destroy'])->name('batch.destroy');
        });

        // application routes
        Route::group(['prefix' => 'applications'], function () {
            Route::get('/', [App\Http\Controllers\ApplicationController::class, 'index'])->name('applications.index');
            Route::post('/cv/{application}', [App\Http\Controllers\ApplicationController::class, 'cv'])->name('applications.cv');
            Route::get('/update/{application}', [App\Http\Controllers\ApplicationController::class, 'update'])->name('applications.update');
            Route::post('/approve/{application}', [App\Http\Controllers\ApplicationController::class, 'approve'])->name('applications.approve');
            Route::post('/reject/{application}', [App\Http\Controllers\ApplicationController::class, 'reject'])->name('applications.reject');
        });

        // user routes
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
            Route::post('/create', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
            Route::get('/show/{user}', [App\Http\Controllers\UserController::class, 'show'])->name('users.show');
            Route::get('/edit/{user}', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
            Route::put('/update/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
            Route::delete('/destroy/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
        });
    });
});
