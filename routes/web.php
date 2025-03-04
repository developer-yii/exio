<?php

use App\Http\Controllers\Frondend\ContactController;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\CheckAndMatchPropertyController;
use App\Http\Controllers\Frontend\CityController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\SocialController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::name('front.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::middleware('guest')->group(function () {
        Route::prefix('check-and-match-property')->group(function () {
            Route::get('/', [CheckAndMatchPropertyController::class, 'checkAndMatchProperty'])->name('check-and-match-property');
            Route::get('/result', [CheckAndMatchPropertyController::class, 'checkAndMatchPropertyResult'])->name('check-and-match-property.result');
            Route::post('/submit', [CheckAndMatchPropertyController::class, 'checkAndMatchPropertySubmit'])->name('check-and-match-property.submit');
            Route::get('/get-amenities', [CheckAndMatchPropertyController::class, 'getAmenities'])->name('check-and-match-property.get-amenities');
        });
    });
});

Route::get('redirect/{provider}', [SocialController::class, 'redirect'])->name('social');
Route::get('callback/{provider}', [SocialController::class, 'callback'])->name('social.callback');

Route::middleware('guest')->group(function () {

    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/verify-account/{token}', [AuthController::class, 'verifyAccount'])->name('verify-account');
    Route::post('/resend/mail', [AuthController::class, 'resendVerificationMail'])->name('resendverificationmail');

    Route::get('terms-and-condition', [PageController::class, 'termsCondition'])->name('terms-condition');
    Route::get('privacy-policy', [PageController::class, 'privacyPolicy'])->name('privacy-policy');
    Route::get('about-us', [PageController::class, 'aboutUs'])->name('about-us');

    Route::get('contact-us', [ContactController::class, 'index'])->name('contact-us');
    Route::post('contact-us/submit', [ContactController::class, 'submit'])->name('contact.submit');
});

Route::middleware(['auth', 'isUser'])->group(function () {});

Route::middleware(['auth'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

require __DIR__ . '/admin.php';
