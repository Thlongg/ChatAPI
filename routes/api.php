<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Conversation;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('login', 'login')->name('users.login');
    Route::post('register', 'register')->name('users.register');
    Route::controller(AuthController::class)->middleware('auth:api')->group(function () {
        Route::delete('logout', 'logout');
        Route::get('me', 'user');
    });
});

Route::controller(ConversationController::class)->prefix('chat')->middleware('auth:api')->group(function () {
    Route::get('coversations', 'get_user_login_coversations')->name('conversation.index');
    Route::get('messages', 'get_messages_in_conversation')->name('message.index');
    Route::get('data_conversation', 'get_data_conversation')->name('conversation.data');
    Route::delete('delete_conversation', 'delete_conversation')->name('conversation.delete');
    Route::post('create_conversation', 'create_conversation')->name('conversation.create');
    Route::post('join_conversation', 'join_conversation')->name('conversation.join');
    Route::post('add_user_to_conversation', 'add_user_to_conversation')->name('conversation.add');
    Route::post('change_conversation_name', 'change_conversation_name')->name('conversation.change');
    Route::delete('leave_conversation', 'leave_conversation')->name('conversation.left');
    Route::delete('remove_from_conversation', 'remove_from_conversation')->name('conversation.remove');
});

Route::controller(MessageController::class)->middleware('auth:api')->group(function () {
    Route::post('send_message', 'send_message')->name('message.send');
});

Route::controller(UserController::class)->prefix('user')->middleware('auth:api')->group(function () {
    Route::get('get_users', 'get_users')->name('users.list');
    Route::post('search_by_name', 'search_by_name')->name('users.search');
    Route::post('change_user_name', 'change_user_name')->name('users.change');
    Route::post('update_user_avatar', 'update_user_avatar')->name('users.update');
});
