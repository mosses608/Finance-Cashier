<?php

use Illuminate\Support\Facades\Route;

Route::post('/auth0', [App\Http\Controllers\Authenticates\AuthenticateController::class, 'authentication'])->name('auth0.user');