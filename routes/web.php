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

Route::prefix('barang')->group(function(){
    Route::get('/', [\App\Http\Controllers\BarangController::class, 'index'])->name('barang.index');
    Route::post('/add', [\App\Http\Controllers\BarangController::class, 'create'])->name('barang.create');
    Route::post('/delete', [\App\Http\Controllers\BarangController::class, 'delete'])->name('barang.delete');
    Route::post('/edit', [\App\Http\Controllers\BarangController::class, 'edit'])->name('barang.edit');
});

Route::get('/dashboard', [\App\Http\Controllers\HomeController::class, 'indexUser'])->middleware(['auth', 'role:user'])->name('dashboard');

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function (){
    Route::get('/', [\App\Http\Controllers\HomeController::class, 'indexAdmin']);
});

Route::prefix('staff')->middleware(['auth', 'role:staff'])->group(function (){
    Route::get('/', [\App\Http\Controllers\HomeController::class, 'indexStaff']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
