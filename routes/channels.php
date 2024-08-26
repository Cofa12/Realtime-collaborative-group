<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['auth']]);

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
//    return array('name' => $user->name);
});

//Broadcast::channel('private-textArea.{user_id}', function ($user, $userId) {
//    return $user->id === (int)$userId;
//});
//Broadcast::channel('private.invitation.{id}',function ($user,$id){
////    return array('name' => $user->name);
//    return false;
//});
