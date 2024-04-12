<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Cfit3aController;
use App\Http\Controllers\Test\NVAController;
use App\Http\Controllers\Test\TIUController;
use App\Http\Controllers\Test\WPTController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Test\EPPSController;
use App\Http\Controllers\Test\MBTIController;
use App\Http\Controllers\Test\MSDTController;
use App\Http\Controllers\Test\TikiController;
use App\Http\Controllers\UserLoginController;
use App\Http\Controllers\Test\TikiDController;
use App\Http\Controllers\Test\DISC24Controller;
use App\Http\Controllers\Test\DISC40Controller;
use App\Http\Controllers\Test\AssesmentController;
use App\Http\Controllers\Test\Assesment10Controller;
use App\Http\Controllers\Test\Assesment20Controller;
use App\Http\Controllers\Test\PapikostickController;

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
	Route::get('/tes/msdt/{id}',[MSDTController::class,'getData'])->name('admin.msdt.next');
	Route::get('/tes/disc-40-soal/{id}',[DISC40Controller::class,'getData'])->name('admin.disc40.next');
	Route::get('/tes/disc-24-soal/{id}',[DISC24Controller::class,'getData'])->name('admin.disc24.next');
	Route::get('/tes/assesment/{id}',[AssesmentController::class,'getData'])->name('admin.assesment.next');
	Route::get('/tes/assesment10/{id}',[Assesment10Controller::class,'getData'])->name('admin.assesment10.next');
	Route::get('/tes/assesment20/{id}',[Assesment20Controller::class,'getData'])->name('admin.assesment20.next');
	Route::get('/tes/papikostick/{id}',[PapikostickController::class,'getData'])->name('admin.papikostick.next');
	Route::get('/tes/tiu/{id}',[TIUController::class,'getData'])->name('admin.tiu.next');
	Route::get('/tes/wpt/{id}',[WPTController::class,'getData'])->name('admin.wpt.next');
	Route::get('/tes/mbti/{id}',[MBTIController::class,'getData'])->name('admin.mbti.next');
	Route::get('/tes/tiki/{part}/{id}',[TikiController::class,'getData'])->name('admin.tiki.next');
	Route::get('/tes/tikid/{part}/{id}',[TikiDController::class,'getData'])->name('admin.tikid.next');
	Route::get('/tes/cifit3A/{part}/{id}',[Cfit3aController::class,'getData'])->name('admin.cfit3a.next');
	Route::get('/tes/epps/{id}',[EPPSController::class,'getData'])->name('admin.epps.next');
	Route::get('/tes/numerik-40/{part}/{id}',[NVAController::class,'getData'])->name('admin.numerik.next');
	Route::get('/tes/verbal60/{part}/{id}',[NVAController::class,'getVerbal'])->name('admin.verbal60.next');

});


// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
