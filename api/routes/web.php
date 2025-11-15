<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Simple health check to validate server & routing quickly
Route::get('/health', function () {
    return response('ok', 200);
});
