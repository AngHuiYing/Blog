
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

//Route::get('/', function () {
    //return view('welcome');
//});

Route::get('/',[PostController::class, 'index'])->name('posts.index');
Route::post('/store',[PostController::class, 'store'])->name('posts.store');
Route::get('/create',[PostController::class, 'create'])->name('posts.create');
Route::get('/edit/{id}',[PostController::class, 'edit'])->name('edit');

Route::put('/update/{id}',[PostController::class, 'update'])->name('update');

Route::delete('/delete/{id}',[PostController::class, 'delete'])->name('delete');

