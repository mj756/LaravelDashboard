<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Response;
use Illuminate\Routing\ResponseFactory;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         Response::macro('error', function ($value) {
            return response()->json(['status'=>500,'data'=>null,'message'=> 'Internal server error'],500);//->header('Content-Type', 'application/json');
         });

         Response::macro('validationError', function ($statusCode,$value) {
            return response()->json(['status'=>$statusCode,'data'=>null,'message'=>$value],200);//->header('Content-Type', 'application/json');
         });

        Response::macro('toJson', function ($statusCode=0,$value=null) {
                $message='error occured';
                $code=200;
                switch($statusCode){
                    case(0):
                         $message='Success!';
                        break;
                    case(101):
                        $message='User not found';
                        break;
                    case(102):
                        $message='User already exist';
                        break;
                    case(103):
                        $message='Invalid credential';
                        break;
                    case(104):
                        $message="Incorrect password";
                        break;   
                    case(105):
                        $status=0;
                        $message="Password changed successfully";
                        break; 
                    case(106):
                            $message="Please provide valid user id";
                            break;
                    case(401):  
                            $code=401;                   
                            $message="Invalid authorization token";
                        break;
                    case 505:
                            $message="Too many request";
                            break;
                }
                return response()->json(['status'=>$statusCode,'data'=>$value,'message'=>$message],$code);//->header('Content-Type', 'application/json');
        });
    }
}
