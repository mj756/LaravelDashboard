<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\MessageController;
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
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/


Route::middleware('auth:api')->group(function () {
        Route::group(['prefix' => 'user'], function () {
            Route::post('/login',[UserController::class,'login'])->withoutMiddleware('auth:api');
            Route::post('/sociallogin',[UserController::class,'socialLogin'])->withoutMiddleware('auth:api');
            Route::post('/register',[UserController::class,'register'])->withoutMiddleware('auth:api');
            Route::post('/forgotpassword',[UserController::class,'forgotPassword'])->withoutMiddleware('auth:api');
          
            Route::post('/logout',[UserController::class,'logout']);
            Route::post('/profile',[UserController::class,'changeProfile']); 
            Route::post('/changepassword',[UserController::class,'changePassword']);
            Route::delete('/deleteuser',[UserController::class,'deleteUser']);
            Route::get('/',[UserController::class,'index']);    
            Route::post('upload',[UserController::class,'changeprofileImage']);      
            Route::post('updatedetail',[UserController::class,'updateDetail']);  
            Route::get('getalluser',[UserController::class,'getAllUser']);  
        });

        Route::group(['prefix' => 'message'], function () {
                Route::post('/',[MessageController::class,'sendPushNotification']);  
                Route::post('/getMessages',[MessageController::class,'getMessages']);     
        });
});
  
   Route::fallback(function (){
   return response()->json([
                'status' => 404,
                'data'=>null,
                'message' => 'API route not found',
                ], 404
            );
 });
