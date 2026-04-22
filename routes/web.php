<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Tree\ExportController;
use App\Http\Controllers\Tree\PersonController;
use App\Http\Controllers\Tree\RelationshipController;
use App\Http\Controllers\Tree\SearchController;
use App\Http\Controllers\Tree\TreeController;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::view('/', 'public.home')->name('home');
Route::view('/how-it-works', 'public.how')->name('how');
Route::view('/faq', 'public.faq')->name('faq');
Route::view('/privacy', 'public.privacy')->name('privacy');
Route::view('/terms', 'public.terms')->name('terms');
Route::view('/contact', 'public.contact')->name('contact');
Route::view('/support', 'public.support')->name('support');
Route::view('/robots.txt', 'public.robots')->name('robots');
Route::view('/sitemap.xml', 'public.sitemap')->name('sitemap');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:auth')->name('login.attempt');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/forgot-password', [PasswordController::class, 'requestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordController::class, 'sendResetLink'])->middleware('throttle:auth')->name('password.email');
    Route::get('/reset-password/{token}', [PasswordController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'not_blocked'])->group(function (): void {
    Route::get('/email/verify', fn () => view('auth.verify-email'))->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('dashboard')->with('status', 'Email подтвержден.');
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'Письмо отправлено.');
    })->middleware('throttle:6,1')->name('verification.send');

    Route::middleware(EnsureEmailIsVerified::class)->group(function (): void {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        Route::get('/trees', [TreeController::class, 'index'])->name('trees.index');
        Route::post('/trees', [TreeController::class, 'store'])->name('trees.store');
        Route::get('/trees/{tree}', [TreeController::class, 'show'])->name('trees.show');
        Route::patch('/trees/{tree}', [TreeController::class, 'update'])->name('trees.update');
        Route::post('/trees/{tree}/archive', [TreeController::class, 'archive'])->name('trees.archive');
        Route::delete('/trees/{tree}', [TreeController::class, 'destroy'])->name('trees.destroy');
        Route::post('/trees/{tree}/viewport', [TreeController::class, 'saveViewport'])->name('trees.viewport');

        Route::post('/trees/{tree}/people', [PersonController::class, 'store'])->middleware('throttle:upload')->name('people.store');
        Route::patch('/trees/{tree}/people/{person}', [PersonController::class, 'update'])->middleware('throttle:upload')->name('people.update');
        Route::delete('/trees/{tree}/people/{person}', [PersonController::class, 'destroy'])->name('people.destroy');
        Route::delete('/trees/{tree}/people/{person}/photo', [PersonController::class, 'deletePhoto'])->name('people.photo.delete');
        Route::get('/trees/{tree}/people/{person}/photo', [PersonController::class, 'photo'])->name('people.photo');

        Route::post('/trees/{tree}/relationships', [RelationshipController::class, 'store'])->name('relationships.store');
        Route::delete('/trees/{tree}/relationships/{relationship}', [RelationshipController::class, 'destroy'])->name('relationships.destroy');

        Route::get('/trees/{tree}/search', SearchController::class)->middleware('throttle:search')->name('trees.search');
        Route::post('/trees/{tree}/export/png', [ExportController::class, 'png'])->middleware('throttle:export')->name('trees.export.png');
        Route::get('/trees/{tree}/export/pdf', [ExportController::class, 'pdf'])->middleware('throttle:export')->name('trees.export.pdf');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::middleware('admin')->prefix('admin')->name('admin.')->group(function (): void {
            Route::get('/', [AdminController::class, 'index'])->name('index');
            Route::post('/users/{user}/block-toggle', [AdminController::class, 'toggleBlock'])->name('users.toggle-block');
        });
    });
});
