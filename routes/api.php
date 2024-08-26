<?php

use App\Http\Controllers\PusherAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use \App\Http\Controllers\actions\GroupController;
use Pusher\Pusher;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// authentications
Route::post('/login',[AuthController::class,'login'])->middleware('ValidationCredentials');
Route::post('/register',[AuthController::class,'signUp']);

// process
Route::group(['middleware'=>['CheckToken']],function () {
    Route::post('/createGroup', [GroupController::class, 'createGroup']);
    Route::post('/createGroup/{id}/invite', [GroupController::class, 'inviteMemeber']);


    Route::group(['middleware'=>['CheckUserExist']],function (){
        Route::get('/user/{id}/invitations', [GroupController::class, 'UserInvitation']);
        Route::get('/user/{id}/invitations/{notification_id}', [GroupController::class, 'getInvitation']);
        Route::put('/user/{id}/invitations/{notification_id}/confirm', [GroupController::class, 'confirmInvitation']);

        Route::get('/user/{id}/groups/', [GroupController::class, 'getGroups']);

        Route::group(['prefix'=>'/user/{id}/'],function (){
            Route::resource('posts',\App\Http\Controllers\actions\PostController::class);

        });
    });
});
Route::post('/userTyping',function (Request $request){
    $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env("PUSHER_APP_ID"), ['cluster'=>'eu']);
    $pusher->trigger('User-typing', 'typing-Event', ['message' => $request->text]);
});
Route::view('notificatinos','checkNotifications');
Route::view('notificatinos2','checkNotifications2');
