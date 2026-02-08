<?php

use App\Http\Controllers\User\Collaboration\ProjectBoardController as CollaborationProjectBoardController;
use App\Http\Controllers\User\Collaboration\ProjectModuleController as CollaborationProjectModuleController;
use App\Http\Controllers\User\Collaboration\TaskController as CollaborationTaskController;
use App\Http\Controllers\User\CommentController;
use App\Http\Controllers\User\DailyDigestController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\DocumentationController;
use App\Http\Controllers\User\DocumentationPagesController;
use App\Http\Controllers\User\EducationController;
use App\Http\Controllers\User\FolderFactoryController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\PasswordLockerController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ProjectBoardController;
use App\Http\Controllers\User\ProjectModuleController;
use App\Http\Controllers\User\SettingsController;
use App\Http\Controllers\User\SubTaskController;
use App\Http\Controllers\User\SyntaxStoreController;
use App\Http\Controllers\User\TaskController;
use App\Http\Controllers\User\ThinkPadController;
use App\Http\Controllers\User\UserSkillController;
use App\Http\Controllers\User\WorkExperienceController;
use App\Models\Documentation;
use Illuminate\Support\Facades\Route;

Route::controller(DashboardController::class)->group(function () {
    Route::get('dashboard', 'index')->name('dashboard');
});

Route::controller(DailyDigestController::class)->group(function () {
    Route::get('daily-digest', 'index')->name('daily-digest');
    Route::get('daily-digest/me', 'myDigestions')->name('daily-digest.me');
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
    Route::get('think-pad/me', 'myThinkPads')->name('think-pad.me');
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
    Route::get('syntax-store/me', 'mySyntaxStores')->name('syntax-store.me');
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
    Route::post('folder-factory/{folderFactory}', 'update')->name('folder-factory.update');
    Route::delete('folder-factory/{folderFactory}', 'destroy')->name('folder-factory.delete');
    Route::get('folder-factory/files/create', 'create')->name('folder-factory.files.create');
    Route::get('folder-factory/{slug}/files', 'showFiles')->name('folder-factory.files.index');
    Route::get('/ff-files/upload/status', 'uploadStatus')->name('folder-factory.file.upload.status');
    Route::post('/ff-files/upload', 'uploadChunk')->name('folder-factory.files.upload.chunk');
    Route::post('/ff-files/upload/cancel', 'cancelUpload')->name('folder-factory.files.upload.cancel');
});

Route::controller(ProjectBoardController::class)->group(function () {
    Route::get('project-boards', 'index')->name('project-board');
    Route::get('project-boards/create', 'create')->name('project-board.create');
    Route::post('project-boards', 'store')->name('project-board.store');
    Route::get('project-boards/{slug}/edit', 'edit')->name('project-board.edit');
    Route::post('project-boards/{slug}', 'update')->name('project-board.update');
    Route::get('project-boards/{slug}', 'show')->name('project-board.show');
    Route::delete('project-boards/{slug}', 'destroy')->name('project-board.delete');
});

Route::controller(ProjectModuleController::class)->group(function () {
    Route::get('project-boards/{slug}/modules', 'index')->name('project-board.modules.index');
    Route::get('project-boards/{slug}/modules/create', 'createNested')->name('project-board.modules.create');
    Route::post('project-boards/{slug}/modules', 'store')->name('project-board.modules.save');
    Route::get('project-boards/{slug}/modules/{module}', 'show')->name('project-board.modules.show');
    Route::get('project-boards/{slug}/modules/{module}/edit', 'editNested')->name('project-board.modules.edit');;
    Route::post('project-boards/{slug}/modules/{module}', 'update')->name('project-board.modules.update');
    Route::delete('project-boards/{slug}/modules/{module}', 'destroy')->name('project-board.modules.delete');
    Route::get('modules', 'index')->name('modules.index');
    Route::get('modules/create', 'create')->name('modules.create');
    Route::post('modules', 'store')->name('modules.save');
    Route::get('modules/{module}/edit', 'edit')->name('modules.edit');
    Route::post('modules/{module}', 'updateGlobal')->name('modules.update');
    Route::get('modules/{module}', 'showGlobal')->name('modules.show');
});

Route::controller(TaskController::class)->group(function () {
    Route::get('project-boards/{slug}/modules/{module}/tasks/create', 'createNested')->name('project-board.modules.tasks.createNested');
    Route::post('project-boards/{slug}/modules/{module}/tasks', 'store')->name('project-board.modules.tasks.store');
    Route::get('project-boards/{slug}/modules/{module}/tasks/{task}/edit', 'editNested')->name('project-board.modules.tasks.editNested');
    Route::post('project-boards/{slug}/modules/{module}/tasks/{task}', 'updateNested')->whereUuid('task')->name('project-board.modules.tasks.update');
    Route::get('tasks', 'index')->name('tasks.index');
    Route::post('tasks', 'store')->name('tasks.store');
    Route::get('tasks/create', 'createGlobal')->name('tasks.create');
    Route::get('tasks/{task}/edit', 'editGlobal')->name('tasks.edit');
    Route::post('tasks/{task}', 'update')->name('tasks.update');
    Route::delete('tasks/{task}', 'destroy')->name('tasks.delete');
    Route::get('tasks/{task}', 'show')->name('tasks.show');
    Route::patch('tasks/{task}/status/{status}', 'updateStatus')->name('tasks.updateStatus');
});

Route::controller(SubTaskController::class)->group(function () {
    Route::post('tasks/{task}/sub-tasks', 'store')->name('tasks.sub_task.store');
    Route::delete('sub-tasks/{subTask}', 'destroy')->name('sub_task.delete');
    Route::post('sub-tasks/{subTask}', 'update')->name('sub_task.update');
});

Route::controller(ProfileController::class)->group(function () {
    Route::get('profile', 'index')->name('profile.index');
    Route::put('profile/personal-information', 'updatePersonalInformation')->name('profile.personal_information.update');
    Route::post('profile/address', 'saveAddress')->name('profile.address.save');
    Route::post('profile/social-media-links', 'updateSocialMediaLinks')->name('profile.social_media_links.save');
    Route::post('profile/profile-image', 'updateProfileImage')->name('profile.profile_image.save');
});

Route::controller(UserSkillController::class)->group(function () {
    Route::post('profile/user-skill', 'store')->name('profile.user_skill.save');
    Route::delete('profile/user-skill/{userSkill}', 'destroy')->name('profile.user_skill.delete');
});

Route::prefix('collab')->name('collab.all.')->middleware('collab')->group(function () {
    Route::controller(CollaborationProjectBoardController::class)->group(function () {
        Route::get('project-boards', 'index')->name('project-board.index');
    });
    Route::controller(CollaborationProjectModuleController::class)->group(function () {
        Route::get('modules', 'index')->name('modules.index');
    });
    Route::controller(CollaborationTaskController::class)->group(function () {
        Route::get('tasks', 'index')->name('tasks.index');
    });
});
Route::prefix('collab/{owner?}/')->name('collab.')->middleware('collab')->group(function () {
    Route::controller(CollaborationProjectBoardController::class)->group(function () {
        Route::get('project-boards', 'index')->name('project-board.index');
        Route::get('project-boards/{slug}', 'show')->name('project-board.show');
    });
    Route::controller(CollaborationProjectModuleController::class)->group(function () {
        Route::get('modules', 'index')->name('modules.index');
        Route::get('project-boards/{slug}/modules/{module}', 'show')->name('modules.show');
    });
    Route::controller(CollaborationTaskController::class)->group(function () {
        Route::get('tasks', 'index')->name('tasks.index');
        Route::get('tasks/{task}', 'show')->name('tasks.show');
    });
});

Route::controller(PasswordLockerController::class)->group(function () {
    Route::get('password-locker', 'index')->name('password_locker.index');
    Route::post('password-locker', 'store')->name('password_locker.save');
    Route::post('password-locker/{passwordLocker}', 'update')->name('password_locker.update');
    Route::delete('password-locker/{passwordLocker}', 'destroy')->name('password_locker.delete');
    Route::get('password-locker/{passwordLocker}/reveal-password', 'showPassword')->name('password_locker.showPassword');
});

Route::controller(WorkExperienceController::class)->group(function () {
    Route::post('work-experience', 'store')->name('work_experience.save');
    Route::post('work-experience/{workExperience}', 'update')->name('work_experience.update');
    Route::delete('work-experience/{workExperience}', 'destroy')->name('work_experience.delete');
});

Route::controller(EducationController::class)->group(function () {
    Route::post('education', 'store')->name('education.save');
    Route::post('education/{education}', 'update')->name('education.update');
    Route::delete('education/{education}', 'destroy')->name('education.delete');
});

Route::controller(SettingsController::class)->group(function () {
    Route::get('settings', 'index')->name('settings.index');
});

Route::controller(NotificationController::class)->group(function () {
    Route::get('notifications', 'index')->name('notifications.index');
});

Route::controller(DocumentationController::class)->group(function () {
    Route::get('documentation', 'index')->name('documentation.index');
    Route::get('documentation/new', 'create')->name('documentation.create');
    Route::post('documentation', 'store')->name('documentation.store');
    Route::get('documentation/{documentation}/edit', 'edit')->name('documentation.edit');
    Route::post('documentation/{documentation}', 'update')->name('documentation.update');

});

Route::controller(DocumentationPagesController::class)->group(function(){
    Route::get('documentation/{documentation}/pages', 'index')->name('documentation.pages.index');

    Route::get('documentation/{documentation}/get', 'getDocumentationPage')->name('documentation.pages.get');
    Route::post('documentation/{documentation}/pages/create', 'createPage')->name('documentation.pages.create');
    Route::patch('documentation/{docPage}/update-content', 'updateContent')->name('documentation.pages.udpate.content');
    Route::patch('documentation/{docPage}/update-content-git', 'loadContentFromGit')->name('documentation.pages.git.load.content');


});
