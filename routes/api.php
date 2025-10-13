<?php

use App\Http\Controllers\Api\ProjectBoardController;
use App\Http\Controllers\Api\ProjectModuleController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('project-board/{projectBoard}/modules', [ProjectBoardController::class, 'getModules'])->name('project-board.modules');

    Route::get('project-modules/{projectModule}/assignees', [ProjectModuleController::class, 'getAssignees'])->name('project-module.getAssignees');
});

Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);
    return ['token' => $token->plainTextToken];
});

Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = $user->createToken($request->email)->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => $user,
    ]);
});
