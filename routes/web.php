<?php

use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\DepositController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('login');
})->name('home');

// All Auth Related Route
 Route::get('/register', [RegisterController::class, 'registrationForm'])->name('registrationForm');
 Route::post('/register', [RegisterController::class, 'register'])->name('registerUser')->middleware(['viewonly']);
Route::get('/login', [LoginController::class, 'loginForm'])->name('loginForm');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Handling All Admin Routes
Route::get('/admin/{any?}', function () {
    if (Auth::check()) {
        return view('admin.app');
    }
    return redirect('login');
})->where('any', '.*')->name('admin');

// All route available only for authenticated users
Route::group(['prefix' => 'api', 'middleware' => ['auth']], function () {

    Route::get('/users/authenticated-user', [UserController::class, 'getAuthenticatedUser']);

    Route::resource('members', MemberController::class);
    Route::resource('deposits', DepositController::class);
    Route::resource('expenses', ExpenseController::class);

    Route::get('/dashboard-reports', [ReportController::class, 'getDashBoardReports']);
});
