<?php

use App\Http\Controllers\ConstantController;
use App\Http\Controllers\EngineerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
})->name('login');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [HomeController::class, 'index'])
        ->name('dashboard');
    Route::resource('constants', ConstantController::class);

    Route::prefix('engineers')->name('engineers.')->group(function () {
        Route::get('/', [EngineerController::class, 'index'])->name('index');
        Route::get('/create', [EngineerController::class, 'create'])->name('create');
        Route::post('/', [EngineerController::class, 'store'])->name('store');
        Route::get('/{engineer}', [EngineerController::class, 'show'])->name('show');
        Route::get('/{engineer}/edit', [EngineerController::class, 'edit'])->name('edit');
        Route::put('/{engineer}', [EngineerController::class, 'update'])->name('update');
        Route::delete('/{engineer}', [EngineerController::class, 'destroy'])->name('destroy');
        
        Route::get('/cities/{governorate}', [EngineerController::class, 'getCities'])->name('cities');
    });

});

require __DIR__.'/auth.php';
