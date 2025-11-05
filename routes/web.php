<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FileCategoryController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/files', [FileController::class,'index'])->name('files.index');
    Route::post('/files', [FileController::class,'store'])->name('files.store');
    Route::get('/files/trash', [FileController::class,'trash'])->name('files.trash');
    Route::delete('/files/{file}', [FileController::class,'destroy'])->name('files.destroy');
    Route::get('/files/{file}/view', [FileController::class,'view'])->name('files.view');
    Route::get('/files/{file}/details', [FileController::class,'details'])->name('files.details');
    Route::get('/files/{file}/download', [FileController::class,'download'])->name('files.download');

    Route::post('/files/{file}/share', [FileController::class,'share'])->name('files.share');
    Route::get('/shared/{token}', [FileController::class,'shared'])->name('files.shared');

    // edit and comment functionality
    Route::get('/files/{file}/edit', [FileController::class,'edit'])->name('files.edit');
    Route::put('/files/{file}/update', [FileController::class,'update'])->name('files.update');
    Route::post('/files/{file}/comment', [FileController::class,'comment'])->name('files.comment');
    Route::get('/files/{file}/comments', [FileController::class,'getComments'])->name('files.comments');

    // sharing functionality
    Route::get('/files/{file}/shares', [FileController::class,'getShares'])->name('files.shares');
    Route::get('/files/{file}/generate-link', [FileController::class,'generateShareableLink'])->name('files.generate-link');
    Route::patch('/shares/{share}', [FileController::class,'updateShare'])->name('shares.update');
    Route::delete('/shares/{share}', [FileController::class,'removeShare'])->name('shares.remove');

    Route::post('/files/{id}/restore', [FileController::class,'restore'])->name('files.restore');
    Route::delete('/files/{id}/forceDelete', [FileController::class,'forceDelete'])->name('files.forceDelete');

    // signing
    Route::get('/files/{file}/sign', [SignatureController::class,'show'])->name('files.sign');
    Route::post('/files/{file}/sign', [SignatureController::class,'store'])->name('files.sign.store');
    Route::get('/files/{file}/signature', [SignatureController::class,'view'])->name('files.signature.view');

    // resources for departments and categories
    Route::resource('departments', DepartmentController::class);
    Route::resource('categories', FileCategoryController::class);

    // User management
    Route::resource('users', UserController::class);

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');
});
