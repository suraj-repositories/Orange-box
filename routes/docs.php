<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Docs\DocumentationController;
use App\Http\Controllers\Docs\DocumentationDocumentController;
use App\Http\Controllers\Docs\DocumentationFeedbackController;
use App\Http\Controllers\Docs\FaqController;
use App\Http\Controllers\Docs\PartnersController;
use App\Http\Controllers\Docs\ReleaseController;
use App\Http\Controllers\Docs\SponsorsController;
use App\Http\Controllers\Docs\UserActivityController;

Route::get(
    '/{user:username}/docs/{slug}/switch/{version}/{path?}',
    [DocumentationController::class, 'switchVersion']
)->where('path', '.*')
    ->name('docs.switchVersion');

Route::get(
    '/{user:username}/docs/{slug}/{version}/{path?}',
    [DocumentationController::class, 'show']
)->where('path', '.*')
    ->name('docs.show');

Route::get(
    '/{user:username}/docs-extras/{slug}/{version}/{type}',
    [DocumentationDocumentController::class, 'index']
)->name('docs.extras.show');

Route::get('/{user:username}/docs-search/{slug}/{version?}', [DocumentationController::class, 'search'])->name('docs.search');

Route::get('/{user:username}/docs-v/{slug}/releases', [ReleaseController::class, 'index'])->name('docs.releases.index');

Route::get('/{user:username}/docs-xt/{slug}/{version}/faqs', [FaqController::class, 'index'])->name('docs.faq.index');

Route::get('/{user:username}/docs-v/{slug}/{version}/partners-all', [PartnersController::class, 'partnersAll'])->name('docs.partners.all.index');
Route::get('/{user:username}/docs-v/{slug}/{version}/partner/{uuid}', [PartnersController::class, 'show'])->name('docs.partners.show');
Route::post('/{user:username}/docs-v-ajax/{slug}/{version}/partners-search', [PartnersController::class, 'partnersSearchComponent'])->name('docs.partners.search.component');

Route::post('/feedback/save', [DocumentationFeedbackController::class, 'save'])->name('docs.feedback.save');

Route::post('/page-exit', [UserActivityController::class, 'pageExit'])->name('docs.user-activity.page-exit');
