<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserLoginController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// \Ajifatur\Helpers\RouteExt::auth();
// \Ajifatur\Helpers\RouteExt::admin();
// Auth::routes();
Route::group(['middleware' => ['guest']], function() {
    Route::get('/', function () {
        return redirect('login');
     });
    Route::get('/login', [UserLoginController::class, 'showLoginForm']);
    Route::post('/login', [UserLoginController::class, 'login']);
});

// User Capabilities...
Route::group(['middleware' => ['user']], function() {
	// Logout
	Route::post('/logout', [UserLoginController::class, 'logout']);

	// Dashboard
	Route::get('/dashboard', [DashboardController::class, 'index']);

	// Tes
	Route::get('/tes/{path}', [TestController::class,'test']);
	Route::post('/tes/{path}/store', [TestController::class, 'store']);
	Route::post('/tes/{path}/delete', [TestController::class, 'delete']);
});


// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
