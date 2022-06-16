<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use App\Services\ConversationService;
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
    Route::get('coversations', 'getUserLoginCoversations')->name('conversation.index');
    Route::get('messages', 'getMessagesInConversation')->name('message.index');
    Route::get('data_conversation', 'getDataConversation')->name('conversation.data');
    Route::delete('delete_conversation', 'deleteConversation')->name('conversation.delete');
    Route::post('create_conversation', 'createConversation')->name('conversation.create');
    Route::post('join_conversation', 'joinConversation')->name('conversation.join');
    Route::post('add_user_to_conversation', 'addUserToConversation')->name('conversation.add');
    Route::post('change_conversation_name', 'changeConversationName')->name('conversation.change');
    Route::post('update_conversation_avatar', 'updateConversationAvatar')->name('conversation.update');
    Route::delete('leave_conversation', 'leaveConversation')->name('conversation.left');
    Route::delete('remove_from_conversation', 'removeFromConversation')->name('conversation.remove');
});

Route::controller(MessageController::class)->group(function () {
    Route::post('send_message', 'sendMessage')->name('message.send');
});

Route::controller(UserController::class)->prefix('user')->middleware('auth:api')->group(function () {
    Route::get('get_users', 'getUsers')->name('users.list');
    Route::post('search_by_name', 'searchByName')->name('users.search');
    Route::post('change_user_name', 'changeUserName')->name('users.change');
    Route::post('update_user_avatar', 'updateUserAvatar')->name('users.update');
});
