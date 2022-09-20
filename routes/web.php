<?php

use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\MovieController;
use App\Http\Controllers\User\SubscriptionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::redirect('/', '/login');

Route::middleware(['auth', 'role:user'])->prefix('dashboard')->name('user.dashboard.')->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('movie/{movie:slug}', [MovieController::class, 'show'])->name('movie.show');
    Route::get('subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('subscription/{plan}/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
});

Route::prefix('prototype')->name('prototype.')->group(function() {
    Route::get('login', function() {
        return Inertia::render('Prototype/Login');
    })->name('login');

    Route::get('register', function() {
        return Inertia::render('Prototype/Register');
    })->name('register');

    Route::get('dashboard', function() {
        return Inertia::render('Prototype/Dashboard');
    })->name('dashboard');

    Route::get('subscription', function() {
        return Inertia::render('Prototype/Subscription');
    })->name('subscription');

    Route::get('movie/{slug}', function() {
        return Inertia::render('Prototype/Movie/Show');
    })->name('movie.show');
});

require __DIR__.'/auth.php';
