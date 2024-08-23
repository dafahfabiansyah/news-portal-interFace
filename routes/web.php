<?php

use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'index'])->name('frontend.index');

Route::get('/details/{article_news:slug}', [FrontController::class, 'details'])->name('frontend.details');

Route::get('/category/{category:slug}', [FrontController::class, 'category'])->name('frontend.category');

Route::get('/author/{author:slug}', [FrontController::class, 'author'])->name('frontend.author');

Route::get('/search', [FrontController::class, 'search'])->name('frontend.search');
