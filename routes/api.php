<?php
use App\Http\Controllers\Authentications\LoginBasic;

Route::post('/login', [LoginBasic::class, 'login']);