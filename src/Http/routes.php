<?php

use Illuminate\Support\Facades\Route;

$verifyRoute = config('mobile_verifier.routes.verify', 'mobile/verify');
$resendRoute = config('mobile_verifier.routes.resend', 'mobile/resend');

Route::post($verifyRoute, 'MobileVerificationController@verify')->name('mobile.verify');
Route::post($resendRoute, 'MobileVerificationController@resend')->name('mobile.resend');
