<?php

namespace App\Routing;

use App\Contracts\RouteRegistrar;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SignInController;
use App\Http\Controllers\Auth\SignUpController;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Support\Facades\Route;

class AuthRegistrar implements RouteRegistrar
{
    public function map(Registrar $registrar): void
    {
        Route::middleware('web')->group(function (){

            Route::controller(SignInController::class)->group(function (){
                Route::get('/login', 'page')->name('login');

                Route::post('/login', 'handle')
                    ->middleware('throttle:auth')
                    ->name('login.handle');

                Route::delete('/logout', 'logout')->name('logOut');
            });

            Route::controller(SignUpController::class)->group(function (){
                Route::get('/sign-up', 'page')->name('register');

                Route::post('/sign-up', 'handle')
                    ->middleware('throttle:auth')
                    ->name('register.handle');
            });

            Route::controller(ForgotPasswordController::class)->group(function (){
                Route::get('/forgot-password', 'page')
                    ->middleware('guest')
                    ->name('forgot-password');

                Route::post('/forgot-password', 'handle')
                    ->middleware('guest')
                    ->name('forgot-password.handle');
            });

            Route::controller(ResetPasswordController::class)->group(function (){
                Route::get('/reset-password/{token}', 'page')
                    ->middleware('guest')
                    ->name('password.reset');

                Route::post('/reset-password', 'handle')
                    ->middleware('guest')
                    ->name('reset-password.handle');
            });
        });
    }
}
