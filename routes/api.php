<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Posts
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);
Route::post('/posts', [PostController::class, 'store'])->middleware('auth:sanctum');
Route::put('/posts/{id}', [PostController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/posts/{id}', [PostController::class, 'destroy'])->middleware('auth:sanctum');

// Comments
Route::get('/posts/{post_id}/comments', [CommentController::class, 'index']);
Route::post('/posts/{post_id}/comments', [CommentController::class, 'store'])->middleware('auth:sanctum');
Route::put('/comments/{id}', [CommentController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->middleware('auth:sanctum');

// Likes
Route::post('/posts/{post}/like', [LikeController::class, 'likePost'])->middleware('auth:sanctum');
Route::post('/comments/{comment}/like', [LikeController::class, 'likeComment'])->middleware('auth:sanctum');

// Roles
// Route::post('/posts', [PostController::class, 'store'])->middleware(['auth:sanctum', 'role:user']);
// Route::delete('/posts/{post}', [PostController::class, 'destroy'])->middleware(['auth:sanctum', 'role:admin']);

// Admin
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'listUsers']);
    Route::put('/admin/users/{user}/role', [AdminController::class, 'assignRole']);
});

// Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store'])->middleware(['auth:sanctum', 'role:admin']);

// Notification
Route::get('/notifications', function (Request $request) {
    return $request->user()->notifications;
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
});

// Route::get('/protected-route', function () {
//     return 'Protected Content';
// })->middleware('custom');

use App\Models\User;
Route::post('/test-password', function (Illuminate\Http\Request $request) {
    $user = \App\Models\User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    if (!\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
        return response()->json(['error' => 'Password mismatch'], 401);
    }

    return response()->json(['success' => 'Password matches'], 200);
});
