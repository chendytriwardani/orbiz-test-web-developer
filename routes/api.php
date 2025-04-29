<?php

use Illuminate\Support\Facades\Route;

// get the books api
Route::get('/books/api', [BooksController::class, 'api'])->name('books.api');