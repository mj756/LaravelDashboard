<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Illuminate\Support\Str;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Auth\AuthenticationException;
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
   protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
     public function render($request, Throwable $exception)
    {
       
         if(Str::startsWith($request->getRequestUri(),'/api')){
        if ($exception instanceof RouteNotFoundException) {
                return response()->toJson(401);
        }else if($exception instanceof ThrottleRequestsException)
        {
              return response()->toJson(505);
        }else if($exception instanceof AuthenticationException){
                 return response()->toJson(401);
        }
        return response()->error(500);
         }else{
            if ($exception instanceof RouteNotFoundException) {
                return redirect('/unauthorized');
            }
         }
        return parent::render($request, $exception);       
    }
}
