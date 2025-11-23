<?php

use App\Http\Controllers\ConstantController;
use App\Http\Controllers\EngineerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EngineerIssueController;
use App\Http\Controllers\TeamController;
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
        ->name('dashboard')->middleware('permission:dashboard.view');
    Route::resource('constants', ConstantController::class);

// عرض المستخدمين
Route::get('users', [UserController::class, 'index'])
    ->name('users.index')
    ->middleware('permission:users.view');

// صفحة إنشاء مستخدم
Route::get('users/create', [UserController::class, 'create'])
    ->name('users.create')
    ->middleware('permission:users.create');

// حفظ مستخدم جديد
Route::post('users', [UserController::class, 'store'])
    ->name('users.store')
    ->middleware('permission:users.create');

// صفحة تعديل مستخدم
Route::get('users/{id}/edit', [UserController::class, 'edit'])
    ->name('users.edit')
    ->middleware('permission:users.edit');

// تحديث بيانات مستخدم
Route::put('users/{id}', [UserController::class, 'update'])
    ->name('users.update')
    ->middleware('permission:users.edit');

// حذف مستخدم
Route::delete('users/{id}', [UserController::class, 'destroy'])
    ->name('users.destroy')
    ->middleware('permission:users.delete');
    
Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

// جلب صلاحيات الدور عبر AJAX
Route::get('users/get-role-permissions/{roleId}', [UserController::class, 'getRolePermissions'])
    ->name('users.role.permissions')
    ->middleware('permission:users.create'); 

    Route::prefix('engineers')->name('engineers.')->group(function () {
        Route::get('/', [EngineerController::class, 'index'])->name('index')->middleware('permission:engineers.view');
        Route::get('/create', [EngineerController::class, 'create'])->name('create')->middleware('permission:engineers.create');
        Route::post('/', [EngineerController::class, 'store'])->name('store')->middleware('permission:engineers.create');
        Route::get('/{engineer}', [EngineerController::class, 'show'])->name('show')->middleware('permission:engineers.view');
        Route::get('/{engineer}/edit', [EngineerController::class, 'edit'])->name('edit')->middleware('permission:engineers.edit');
        Route::put('/{engineer}', [EngineerController::class, 'update'])->name('update')->middleware('permission:engineers.edit');
        Route::delete('/{engineer}', [EngineerController::class, 'destroy'])->name('destroy')->middleware('permission:engineers.delete');
        
        Route::get('/cities/{governorate}', [EngineerController::class, 'getCities'])->name('cities');
    });

    Route::post('/engineers/{engineer}/issues', [EngineerIssueController::class, 'store'])->name('engineers.issues.store');
    Route::post('/issues/{issue}/status', [EngineerIssueController::class, 'updateStatus'])->name('issues.updateStatus');


    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile.index');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::post('/profile/update', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])
        ->name('profile.change-password');

    Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])
        ->name('profile.update-password');

//     Route::middleware(['permission:teams.create'])->group(function () {
//         Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
//         Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
//     });
// Route::get('/teams/get-engineers/{govId}', function($govId) {
//     return \App\Models\Engineer::where('is_active', true)
//             ->where('home_governorate_id', $govId)
//             ->get();
// })->middleware('auth');

//     Route::middleware(['permission:teams.view'])->group(function () {
//         Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
//         Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');
//     });

//     Route::middleware(['permission:teams.edit'])->group(function () {
//         Route::get('/teams/{team}/edit', [TeamController::class, 'edit'])->name('teams.edit');
//         Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update');
//         Route::patch('/teams/{team}/toggle', [TeamController::class, 'toggleStatus'])->name('teams.toggle');
//     });

//     Route::middleware(['permission:teams.delete'])->group(function () {
//         Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');
//     });

    Route::get('/teams/get-engineers/{governorateId}', [TeamController::class, 'getEngineersByGovernorate'])
         ->name('teams.get-engineers');

        Route::middleware(['permission:teams.create'])->group(function () {
        Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
        Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    });
    
    Route::middleware(['permission:teams.view'])->group(function () {
        Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
        Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');
    });



    Route::middleware(['permission:teams.edit'])->group(function () {
        Route::get('/teams/{team}/edit', [TeamController::class, 'edit'])->name('teams.edit');
        Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update');
        Route::patch('/teams/{team}/toggle', [TeamController::class, 'toggleStatus'])->name('teams.toggle');
    });

    Route::middleware(['permission:teams.delete'])->group(function () {
        Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');
    });


});

require __DIR__.'/auth.php';
