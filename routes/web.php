<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WEB\HomeController;
use App\Http\Controllers\WEB\UserController;

Route::get('/',[UserController::class,'index'])->name('login');
Route::get('/register',[UserController::class,'register'])->name('register');
Route::post('/validateLogin',[UserController::class,'login']);
Route::post('/validateregister',[UserController::class,'validateRegister']);
Route::get('/forgot-password', [UserController::class, 'forgotPassword']);
Route::post('/reset-password', [UserController::class, 'resetPassword']);
Route::middleware('auth')->group(function () {
Route::get('/dashboard',[HomeController::class,'index'])->name('dashboard');
Route::get('/user-profile',[UserController::class,'getUserProfile'])->name('profile');
Route::get('/profile',[UserController::class,'getProfile'])->name('myprofile');
Route::post('/user-profile',[UserController::class,'save'])->name('editProfile');
Route::get('/user-management',[UserController::class,'userManagement'])->name('user-management');
Route::get('/tables',[UserController::class,'getTables'])->name('tables');
Route::get('/billing',[UserController::class,'getBilling'])->name('billing');
Route::get('/virtual-reality',[UserController::class,'getVirtualReality'])->name('virtual-reality');
Route::get('/logout', [UserController::class, 'logout']);
});

/*
Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [HomeController::class, 'home']);
	Route::get('profile', function () {
		return view('profile');
	})->name('profile');
	
    Route::get('static-sign-in', function () {
		return view('static-sign-in');
	})->name('sign-in');

    Route::get('static-sign-up', function () {
		return view('static-sign-up');
	})->name('sign-up');

    Route::get('/logout', [SessionsController::class, 'destroy']);
	Route::get('/user-profile', [InfoUserController::class, 'create']);
	Route::post('/user-profile', [InfoUserController::class, 'store']);
    Route::get('/login', function () {
		return view('dashboard');
	})->name('sign-up');
});



Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create']);
    Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');

});*/

Route::fallback(function () {
  return view('page_not_found');
});
Route::get('/unauthorized', function () {
  return view('unauthorized');
});
