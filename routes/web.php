<?php

use App\Http\Controllers\CalculationController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () { return view('welcome'); })->name("/");

Route::get('/test', [TransactionController::class, 'test'])->name("test");

Route::get('/dashboard', [MainController::class, 'getView'])->name('dashboard');

Route::get('/transactions', [TransactionController::class, 'getView'])->name('transactions');
Route::post('/transaction/create', [TransactionController::class, 'create'])->name("transaction.create");
Route::delete('/transaction/delete', [TransactionController::class, 'delete'])->name("transaction.delete");
Route::patch('/transaction/update', [TransactionController::class, 'update'])->name("transaction.update");

Route::get('/calculation/{month?}', [CalculationController::class, 'getView'])->name('calculation');
Route::post('/calculation/change', [CalculationController::class, 'changeMonth'])->name('calculation.change');
Route::post('/calculation/generate', [CalculationController::class, 'generateValues'])->name('calculation.generate');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
