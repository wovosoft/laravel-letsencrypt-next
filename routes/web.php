<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('download', function (Request $request) {
            return response()->download($request->query('path'))->deleteFileAfterSend();
        })
            ->name('download');
    });

require __DIR__ . '/auth.php';
