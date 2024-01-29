<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware(['guest'])->group(function () {

    Route::get('/', [AuthController::class, 'loginIndex'])->name('login.index');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');

});

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard-admin', [AdminController::class, 'index'])->name('admin.index');

    Route::resource('product', ProductController::class);
});
