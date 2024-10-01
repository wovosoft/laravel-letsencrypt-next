<?php

use Illuminate\Support\Facades\Route;

//Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])
    ->group(function () {

    });

require __DIR__ . '/auth.php';
