<?php

use Fouladgar\MobileVerifier\Http\Controllers\MobileVerificationController;
use Illuminate\Support\Facades\Route;

$routes = config('mobile_verifier.routes');

Route::post($routes['verify'], [MobileVerificationController::class, 'verify'])->name('mobile.verified');
Route::post($routes['resend'], [MobileVerificationController::class, 'resend'])->name('mobile.resend');
