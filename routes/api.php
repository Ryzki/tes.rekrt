<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\DISC24Controller;
use App\Http\Controllers\API\QuestionController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/question', [QuestionController::class, 'index']);
Route::post('/question/auth', [QuestionController::class, 'auth']);
Route::post('/question/submit', [QuestionController::class, 'submit']);
Route::post('/question/example/submit', [QuestionController::class, 'submitExample']);

Route::get('/question/disc-24-soal',[DISC24Controller::class, 'index']);
