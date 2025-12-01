<?php

use App\Http\Controllers\Auth\SmsPasswordResetController;
use App\Http\Controllers\ConstantController;
use App\Http\Controllers\DailyAttendanceController;
use App\Http\Controllers\EngineerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EngineerIssueController;
use App\Http\Controllers\GovernorateSupervisorController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\NotificationController;
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

// AJAX routes for password reset
Route::post('/password/send-code-ajax', [SmsPasswordResetController::class, 'sendCodeAjax'])->name('password.send.code.ajax');
Route::post('/password/verify-code-ajax', [SmsPasswordResetController::class, 'verifyCodeAjax'])->name('password.verify.code.ajax');
Route::post('/password/resend-code-ajax', [SmsPasswordResetController::class, 'resendCodeAjax'])->name('password.resend.code.ajax');
Route::post('/password/reset-ajax', [SmsPasswordResetController::class, 'resetPasswordAjax'])->name('password.reset.ajax');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [HomeController::class, 'index'])
        ->name('dashboard')->middleware('permission:dashboard.view');

    Route::resource('constants', ConstantController::class);

    Route::get('users', [UserController::class, 'index'])
        ->name('users.index')
        ->middleware('permission:users.view');

    Route::get('users/create', [UserController::class, 'create'])
        ->name('users.create')
        ->middleware('permission:users.create');

    Route::post('users', [UserController::class, 'store'])
        ->name('users.store')
        ->middleware('permission:users.create');

    Route::get('users/{id}/edit', [UserController::class, 'edit'])
        ->name('users.edit')
        ->middleware('permission:users.edit');

    Route::put('users/{id}', [UserController::class, 'update'])
        ->name('users.update')
        ->middleware('permission:users.edit');

    Route::delete('users/{id}', [UserController::class, 'destroy'])
        ->name('users.destroy')
        ->middleware('permission:users.delete');

    Route::get('/users/{id}', [UserController::class, 'show'])
        ->name('users.show')
        ->middleware('permission:users.view');

    Route::get('/get-cities/{id}', [UserController::class, 'getCities']);

    Route::get('users/get-role-permissions/{roleId}', [UserController::class, 'getRolePermissions'])
        ->name('users.role.permissions')
        ->middleware('permission:users.create'); 

    Route::get('/get-work-areas/{governorateId}', [UserController::class, 'getWorkAreas']);

    // -------- Engineers --------
    Route::prefix('engineers')->name('engineers.')->group(function () {

        Route::get('/', [EngineerController::class, 'index'])
            ->name('index')->middleware('permission:engineers.view');

        Route::get('/create', [EngineerController::class, 'create'])
            ->name('create')->middleware('permission:engineers.create');

        Route::post('/', [EngineerController::class, 'store'])
            ->name('store')->middleware('permission:engineers.create');

        Route::get('/{engineer}', [EngineerController::class, 'show'])
            ->name('show')->middleware('permission:engineers.view');

        Route::get('/{engineer}/edit', [EngineerController::class, 'edit'])
            ->name('edit')->middleware('permission:engineers.edit');

        Route::put('/{engineer}', [EngineerController::class, 'update'])
            ->name('update')->middleware('permission:engineers.edit');

        Route::delete('/{engineer}', [EngineerController::class, 'destroy'])
            ->name('destroy')->middleware('permission:engineers.delete');

        Route::get('/cities/{governorate}', [EngineerController::class, 'getCities'])
            ->name('cities');
    });

    Route::get('/engineer/profile', 
    [EngineerController::class, 'myProfile']
)->name('engineers.profile')->middleware('permission:profile.view');


    Route::get('/engineers/{engineer}/create-account', 
        [EngineerController::class, 'createEngineerAccount'])
        ->name('engineers.createAccount')
        ->middleware('permission:engineers.edit');

Route::get('/get-work-areas/{gov_id}', [EngineerController::class, 'getWorkAreas']);

    // -------- Issues --------
    Route::post('/engineers/{engineer}/issues', [EngineerIssueController::class, 'store'])
        ->name('engineers.issues.store')
        ->middleware('permission:issues.create');

    Route::post('/issues/{issue}/status', [EngineerIssueController::class, 'updateStatus'])
        ->name('issues.updateStatus')
        ->middleware('permission:issues.edit');

    Route::resource('issues', IssueController::class)
        ->middleware('permission:issues.view');

    Route::post('issues/{issue}/update-status', [IssueController::class, 'updateStatus'])
        ->name('issues.updateStatus')
        ->middleware('permission:issues.edit');


    // -------- Profile --------
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('permission:profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update')->middleware('permission:profile.edit');
    Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');


    // -------- Teams --------
    Route::get('/teams/get-engineers/{governorateId}', 
        [TeamController::class, 'getEngineersByGovernorate'])
        ->name('teams.get-engineers')
        ->middleware('permission:teams.view');

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

    Route::get('/teams/get-sub-codes/{mainId}', [TeamController::class, 'getSubCodes']);
    Route::get('/teams/get-engineers-by-main-code/{mainCodeId}', [TeamController::class, 'getEngineersByMainCode'])->name('teams.getEngineersByMainCode');
    Route::get('/teams/get-main-codes-by-gov/{govId}', [TeamController::class, 'getMainCodesByGovernorate']);

    // -------- Survey Supervisors (Governorate Manager View) --------
    Route::get('/governorate/supervisors', 
        [GovernorateSupervisorController::class, 'index'])
        ->name('governorate.supervisors.index')
        ->middleware('permission:survey.supervisor.view');

    Route::get('/governorate/supervisors/{id}', 
        [GovernorateSupervisorController::class, 'show'])
        ->name('governorate.supervisors.show')
        ->middleware('permission:survey.supervisor.view');

        Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/fetch', [NotificationController::class, 'fetch'])->name('fetch');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('readAll');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('attendance')->name('attendance.')->group(function () {
        
        Route::get('/', [DailyAttendanceController::class, 'index'])
             ->name('index')
             ->middleware('permission:attendance.view');
        
        Route::get('/create', [DailyAttendanceController::class, 'create'])
             ->name('create')
             ->middleware('permission:attendance.create');
        
        Route::post('/store', [DailyAttendanceController::class, 'store'])
             ->name('store')
             ->middleware('permission:attendance.create');
        
        Route::get('/{id}/edit', [DailyAttendanceController::class, 'edit'])
             ->name('edit')
             ->middleware('permission:attendance.edit');
        
        Route::put('/{id}', [DailyAttendanceController::class, 'update'])
             ->name('update')
             ->middleware('permission:attendance.edit');
        
        Route::delete('/{id}', [DailyAttendanceController::class, 'destroy'])
             ->name('destroy')
             ->middleware('permission:attendance.delete');
        
        Route::get('/statistics', [DailyAttendanceController::class, 'statistics'])
             ->name('statistics')
             ->middleware('permission:attendance.view');
        
        Route::post('/check-availability', [DailyAttendanceController::class, 'checkAvailability'])
             ->name('checkAvailability');
    });

    Route::get('/users/profile/{id}', [ProfileController::class, 'show'])
    ->name('profile.view');

});


require __DIR__.'/auth.php';
