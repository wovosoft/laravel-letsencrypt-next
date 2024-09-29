<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\GuestCertificateController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])
    ->group(function () {
        Route::view('dashboard', 'dashboard')
            ->middleware(['auth', 'verified'])
            ->name('dashboard');

        Route::view('profile', 'profile')
            ->middleware(['auth', 'verified'])
            ->name('profile');
        AccountController::routes();
        DomainController::routes();
        CertificateController::routes();
        OrderController::routes();
    });

GuestCertificateController::routes();

require __DIR__ . '/auth.php';
