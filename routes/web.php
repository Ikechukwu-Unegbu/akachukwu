<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\V1\Utilities\AirtimeController;
use App\Http\Controllers\V1\Utilities\DataController;
use App\Http\Controllers\V1\Utilities\ElectricityController;
use App\Http\Controllers\V1\Utilities\TVController;
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

Route::get('/', function () {return view('pages.home.home');});

Route::get('/airtime', [AirtimeController::class, 'index'])->name('airtime.index');
Route::get('/data', [DataController::class, 'index'])->name('data.index');
Route::get('/electricity', [ElectricityController::class, 'index'])->name('electricity.index');
Route::get('/cable-tv', [TVController::class, 'index'])->name('cable.index');
    


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';