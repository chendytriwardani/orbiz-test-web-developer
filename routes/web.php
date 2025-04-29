<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BooksController;




Route::get('/', function () {
    return view('welcome');
});

// Route::get('/products/dataTable', [ProductController::class, 'dataTable'])
// Route::get('/products', [ProductController::class, 'index'])->name('products.index');
// Route::resource('/products', ProductController::class)->except(['create', 'edit']);



Route::get('/books/dataTable', [BooksController::class, 'dataTable']);


Route::get('/books', [BooksController::class, 'index'])->name('books.index');
Route::resource('/books', BooksController::class)->except(['create', 'edit']);
Route::get('/books/api', [BooksController::class, 'fetchFromGoogleBooks']);



// Login Controller
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); 


