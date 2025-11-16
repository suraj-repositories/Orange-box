<?php

use App\Http\Controllers\Common\FileController;
use App\Http\Controllers\Common\NotificationController;
use App\Http\Controllers\Common\PasswordLockerAuthController;
use App\Http\Controllers\Common\PemKeyController;
use App\Http\Controllers\Common\ProjectBoardController;
use App\Http\Controllers\Common\ProjectModuleController;
use App\Http\Controllers\Common\SettingsController;
use App\Http\Controllers\Common\UserController;
use App\Http\Controllers\Test\TestingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/testing', [TestingController::class, 'testing']);

Route::controller(FileController::class)->middleware('auth')->group(function () {
    Route::delete('file/{file}', 'destroy')->name('file.delete');
    Route::patch('file/{file}', 'rename')->name('file.rename');
    Route::patch('file/{file}/ajax', 'ajaxRename')->name('file.rename.ajax');
    Route::get('/secure-file/{file}', [FileController::class, 'show'])->name('secure.file.show')->middleware('signed');
    Route::get('/file/download/{id}', [FileController::class, 'download'])->name('file.download');
    Route::post('/file/delete-by-path', 'destroyEditorFileByPath')->name('file.path.delete');
});

Route::controller(UserController::class)->middleware('auth')->group(function () {
    Route::post('/search/username', 'searchUsers')->name('search.username');
});
Route::middleware('auth')->prefix('ajax')->name('ajax.')->group(function () {
    Route::get('project-board/{projectBoard}/modules', [ProjectBoardController::class, 'getModules'])->name('project-board.modules');
    Route::get('project-modules/{projectModule}/assignees', [ProjectModuleController::class, 'getAssignees'])->name('project-module.getAssignees');

    Route::controller(PemKeyController::class)->group(function () {
        Route::get('pk/generate', 'generateAndDownload')->name('pemkey.generate');
        Route::get('pk/challenge', 'getChallenge')->name('pemkey.getChallenge');
        Route::post('pk/verify', 'verifySignature')->name('pemkey.verify');
    });

    Route::controller(PasswordLockerAuthController::class)->group(function () {
        Route::post('password-locker/auth/send-otp', 'sendEmailOtp')->name('password_locker.auth.email.send-otp');
        Route::post('password-locker/auth/verify-otp', 'verifyEmailOtp')->name('password_locker.auth.email.verify-otp');
        Route::post('password-locker/auth/verify-master-key', 'verfiyMasterKey')->name('password_locker.auth.verify_master_key');
    });

    Route::controller(NotificationController::class)->group(function () {
        Route::put('notificaiton/{notification}/mark-as-read', 'markAsRead')->name('notification.mark-as-read');
        Route::delete('notificaiton/deleteall', 'deleteAllNotifications')->name('notification.delete.all');
        Route::put('notificaiton/clear-all', 'clearNotifications')->name('notification.clear.all');
    });

    Route::controller(SettingsController::class)->group(function () {
        Route::post('settings/account-settings/change-username', 'changeUsername')->name('settings.account.change.username');

        Route::post('settings/secutiy-settings/change-password', 'changePassword')->name('settings.security.change.password');
        Route::post('settings/secutiy-settings/lock-screen-pin', 'setLockScreen')->name('settings.security.update.lock.screen.pin');
        Route::post('settings/secutiy-settings/master-key', 'setMasterKey')->name('settings.security.update.master-key');
        Route::post('settings/secutiy-settings/pem-key', 'setPemKey')->name('settings.security.update.pem-key');

        Route::post('settings/notifications/toggle', 'toggleNotificationSettings')->name('settings.security.notification.toggle');

    });
});
