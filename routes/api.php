<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailController;
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
Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login'])->name('login');
Route::get('/{user}/list', [EmailController::class, 'list']);
Route::post('/{user}/send', [EmailController::class, 'send']);
