<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\DepositController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// All Auth Related Route
Route::get('/login', [LoginController::class, 'loginForm'])->name('loginForm');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::group(['prefix' => 'api'], function () {

    Route::get('users/authenticated-user', [UserController::class, 'getAuthenticatedUser']);

    // Resource routes with auth middleware applied selectively
    Route::resource('members', MemberController::class)->only(['store', 'update', 'destroy'])->middleware('auth');
    Route::resource('deposits', DepositController::class)->only(['store', 'update', 'destroy'])->middleware('auth');
    Route::resource('category', CategoryController::class)->only(['store', 'update', 'destroy'])->middleware('auth');
    Route::resource('expenses', ExpenseController::class)->only(['store', 'update', 'destroy'])->middleware('auth');

    // Public resource routes
    Route::resource('members', MemberController::class)->except(['store', 'update', 'destroy']);
    Route::resource('deposits', DepositController::class)->except(['store', 'update', 'destroy']);
    Route::resource('category', CategoryController::class)->except(['store', 'update', 'destroy']);
    Route::resource('expenses', ExpenseController::class)->except(['store', 'update', 'destroy']);

    // Public route
    Route::get('/dashboard-reports', [ReportController::class, 'getDashBoardReports']);
});

// Handling All Admin Routes
Route::get('/{any?}', function () {
    return view('admin.app');
})->where('any', '.*')->name('home');


