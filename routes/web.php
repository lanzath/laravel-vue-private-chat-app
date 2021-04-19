<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::post('getFriends', [App\Http\Controllers\HomeController::class, 'getFriends']);

Route::post('session/create', [App\Http\Controllers\SessionController::class, 'store']);

Route::post('session/{session}/chats', [App\Http\Controllers\ChatController::class, 'chats']);

Route::post('session/{session}/send', [App\Http\Controllers\ChatController::class, 'send']);

Route::post('session/{session}/read', [App\Http\Controllers\ChatController::class, 'read']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
