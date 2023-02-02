<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('{page}', function () {
    return view('main');
})->where('page', '.*');
