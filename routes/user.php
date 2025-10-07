<?php

use App\Http\Controllers\User\CommentController;
use App\Http\Controllers\User\DailyDigestController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\FolderFactoryController;
use App\Http\Controllers\User\SyntaxStoreController;
use App\Http\Controllers\User\ThinkPadController;
use App\Models\FolderFactory;
use Illuminate\Support\Facades\Route;

Route::controller(DashboardController::class)->group(function () {
    Route::get('dashboard', 'index')->name('dashboard');
});

Route::controller(DailyDigestController::class)->group(function () {
    Route::get('daily-digest', 'index')->name('daily-digest');
    Route::get('daily-digest/create', 'create')->name('daily-digest.create');
    Route::post('daily-digest', 'store')->name('daily-digest.store');
    Route::get('daily-digest/{dailyDigest}', 'show')->name('daily-digest.show');
    Route::get('daily-digest/{dailyDigest}/edit', 'edit')->name('daily-digest.edit');
    Route::post('daily-digest/{dailyDigest}', 'update')->name('daily-digest.update');
    Route::delete('daily-digest/{dailyDigest}', 'destroy')->name('daily-digest.delete');
    Route::post('daily-digest/{dailyDigest}/like', 'like')->name('daily-digest.like');
    Route::post('daily-digest/{dailyDigest}/dislike', 'dislike')->name('daily-digest.dislike');
});

Route::controller(ThinkPadController::class)->group(function () {
    Route::get('think-pad', 'index')->name('think-pad');
    Route::get('think-pad/create', 'create')->name('think-pad.create');
    Route::post('think-pad', 'store')->name('think-pad.store');
    Route::post('think-pad/editor/images/store', 'storeEditorImages')->name('think-pad.editor.images.store');
    Route::get('think-pad/{thinkPad}', 'show')->name('think-pad.show');
    Route::get('think-pad/{thinkPad}/edit', 'edit')->name('think-pad.edit');
    Route::post('think-pad/{thinkPad}', 'update')->name('think-pad.update');
    Route::delete('think-pad/{thinkPad}', 'destroy')->name('think-pad.delete');
    Route::post('think-pad/{thinkPad}/like', 'like')->name('think-pad.like');
    Route::post('think-pad/{thinkPad}/dislike', 'dislike')->name('think-pad.dislike');
});

Route::controller(SyntaxStoreController::class)->group(function () {
    Route::get('syntax-store', 'index')->name('syntax-store');
    Route::get('syntax-store/create', 'create')->name('syntax-store.create');
    Route::post('syntax-store/store', 'store')->name('syntax-store.store');
    Route::get('syntax-store/{syntaxStore}', 'show')->name('syntax-store.show');
    Route::get('syntax-store/{syntaxStore}/edit', 'edit')->name('syntax-store.edit');
    Route::post('syntax-store/{syntaxStore}/update', 'update')->name('syntax-store.update');
    Route::post('syntax-store/editor/images/store', 'storeEditorImages')->name('syntax-store.editor.images.store');
    Route::post('syntax-store/editor/fetch-url-media', 'fetchMediaFromUrl')->name('syntax-store.editor.fetch-url-media');
    Route::get('syntax-store/editor/fetch-url-data', 'fetchUrlData')->name('syntax-store.editor.fetch-url-data');
    Route::delete('syntax-store/{syntaxStore}', 'destroy')->name('syntax-store.delete');
    Route::post('syntax-store/{syntaxStore}/like', 'like')->name('syntax-store.like');
    Route::post('syntax-store/{syntaxStore}/dislike', 'dislike')->name('syntax-store.dislike');
});

Route::controller(CommentController::class)->group(function () {
    Route::post('comments', 'store')->name('comments.store');
    Route::post('comments/load-comments', 'loadComment')->name('comments.load.comments');
    Route::post('comments/load-replies', 'loadReplies')->name('comments.load.replies');
    Route::delete('comments/{comment}', 'destroy')->name('comments.destroy');
    Route::post('comments/{comment}/like', 'like')->name('comments.like');
    Route::post('comments/{comment}/dislike', 'dislike')->name('comments.dislike');
});


Route::controller(FolderFactoryController::class)->group(function () {
    Route::get('folder-factory', 'index')->name('folder-factory');
    Route::post('folder-factory', 'store')->name('folder-factory.save');
    Route::delete('folder-factory/{folderFactory}', 'destroy')->name('folder-factory.delete');
    Route::get('folder-factory/files/create', 'create')->name('folder-factory.files.create');
    Route::get('folder-factory/{slug}/files', 'showFiles')->name('folder-factory.files.index');

    Route::get('/ff-files/upload/status', 'uploadStatus')->name('folder-factory.file.upload.status');
    Route::post('/ff-files/upload', 'uploadChunk')->name('folder-factory.files.upload.chunk');
    Route::post('/ff-files/upload/cancel', 'cancelUpload')->name('folder-factory.files.upload.cancel');
});
