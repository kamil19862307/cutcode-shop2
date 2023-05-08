<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function (){

    Route::get('/login', 'index')->name('login');
    Route::post('/login', 'signIn')->name('signIn');

    #TODO обсудить как быть с именами и конторллерами
    Route::get('/sign-up', 'signUp')->name('signUp');
    Route::post('/sign-up', 'store')->name('store');

    Route::delete('/logout', 'logOut')->name('logout');

    Route::get('/forgot-password', 'forgot')
        ->middleware('guest')
        ->name('password.request');

    Route::post('/forgot-password', 'forgotPassword')
        ->middleware('guest')
        ->name('password.email');

    Route::get('/reset-password/{token}', 'reset')
        ->middleware('guest')
        ->name('password.reset');

    Route::post('/reset-password', 'resetPassword')
        ->middleware('guest')
        ->name('password.update');

    });

Route::get('/', HomeController::class)->name('home');


//Route::get('/', [IndexController::class, 'index'])->name('index');
//Route::get('/', function (){
//    return view('index');
//})->name('home');
//
//
//
//Route::get('/login', function (){
//    return view('auth.index');
//})->name('login');
