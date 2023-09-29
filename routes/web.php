<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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


Route::get('/', [UserController::class, 'index']);
Route::get('/registre', [UserController::class, 'registre']);
Route::get('/forgot', [UserController::class, 'forgot']);
Route::get('/reset/{email}/{token}', [UserController::class, 'reset'])->name('reset');
Route::get('/profile', [UserController::class, 'profile'])->name('profile');
Route::get('/user', [UserController::class, 'user'])->name('user');
Route::get('/fetchAll', [UserController::class, 'fetchAll'])->name('fetchAll');
Route::post('/userAjout', [UserController::class, 'userAjout'])->name('userAjout');
Route::delete('/delete', [UserController::class, 'delete'])->name('delete');

Route::post('/registre', [UserController::class, 'saveUser'])->name('auth.registre');
Route::post('/login', [UserController::class, 'loginUser'])->name('auth.login');
Route::post('/forgot', [UserController::class, 'forgotPassword'])->name('auth.forgot');
Route::get('/logout', [UserController::class, 'logout'])->name('auth.logout');
Route::post('/profile-update', [UserController::class, 'profileUpdate'])->name('profile.update');
Route::post('/profile-image', [UserController::class, 'profileImageUpdate'])->name('profile.image');







