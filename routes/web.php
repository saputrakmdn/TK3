<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::prefix('manage-user')->group(function (){
    Route::get('/', [\App\Http\Controllers\ManageUserController::class, 'index'])->name('user.index');
    Route::post('/create', [\App\Http\Controllers\ManageUserController::class, 'create'])->name('user.create');
    Route::post('/delete', [\App\Http\Controllers\ManageUserController::class, 'delete'])->name('user.delete');
    Route::post('/edit', [\App\Http\Controllers\ManageUserController::class, 'edit'])->name('user.edit');
})->middleware(['auth']);

Route::prefix('barang')->group(function(){
    Route::get('/', [\App\Http\Controllers\BarangController::class, 'index'])->name('barang.index');
    Route::post('/add', [\App\Http\Controllers\BarangController::class, 'create'])->name('barang.create');
    Route::post('/delete', [\App\Http\Controllers\BarangController::class, 'delete'])->name('barang.delete');
    Route::post('/edit', [\App\Http\Controllers\BarangController::class, 'edit'])->name('barang.edit');
})->middleware(['auth', 'role:staff']);

Route::prefix('order')->group(function (){
   Route::get('/', [\App\Http\Controllers\OrderController::class, 'index'])->name('order.index');
   Route::post('/submit', [\App\Http\Controllers\OrderController::class, 'submit'])->name('order.submit');
   Route::get('/list', [\App\Http\Controllers\OrderController::class, 'orderList'])->name('order.list')->middleware(['auth', 'role:staff']);
   Route::post('/change-status', [\App\Http\Controllers\OrderController::class, 'changeStatus'])->name('order.status')->middleware(['auth', 'role:staff']);
});

Route::middleware(['auth', 'role:user'])->group(function (){
    Route::get('/dashboard', [\App\Http\Controllers\HomeController::class, 'indexUser'])->name('user');
});


Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function (){
    Route::get('/', [\App\Http\Controllers\HomeController::class, 'indexAdmin'])->name('admin');
});

Route::prefix('staff')->middleware(['auth', 'role:staff'])->group(function (){
    Route::get('/', [\App\Http\Controllers\HomeController::class, 'indexStaff'])->name('staff');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
