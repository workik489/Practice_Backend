<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProcessImageController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use MongoDB\Client;

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth', ['mode' => 'login']);
    })->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');

    Route::get('/signup', function () {
        return view('auth', ['mode' => 'signup']);
    })->name('signup');
    Route::post('/signup', [AuthController::class, 'register'])->name('signup.store');
    Route::get('/register', function () {
        return redirect()->route('signup');
    })->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/auth/github', [SocialAuthController::class, 'redirectToGithub'])->name('github.redirect');
    Route::get('/auth/github/callback', [SocialAuthController::class, 'handleGithubCallback'])->name('github.callback');
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('/mongo-test', function () {
        $client = new Client(env('MONGODB_URI'));

        return 'MongoDB Connected!';
    });

    Route::get('/test-telescope', function () {
        return 'Telescope Working';
    });

    Route::post('/logout', function () {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');
    
    Route::get('/imageprocess', [ProcessImageController::class, 'upload']);
    Route::resource('users', UserController::class)->except(['show']);
});
    
