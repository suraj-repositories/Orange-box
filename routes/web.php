<?php

use App\Http\Controllers\Common\FileController;
use App\Http\Controllers\Common\ProjectBoardController;
use App\Http\Controllers\Common\ProjectModuleController;
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

Route::controller(UserController::class)->middleware('auth')->group(function(){
    Route::post('/search/username', 'searchUsers')->name('search.username');
});
Route::middleware('auth')->prefix('ajax')->name('ajax.')->group(function(){
    Route::get('project-board/{projectBoard}/modules', [ProjectBoardController::class, 'getModules'])->name('project-board.modules');
    Route::get('project-modules/{projectModule}/assignees', [ProjectModuleController::class, 'getAssignees'])->name('project-module.getAssignees');

});

