<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PusherAuthController extends Controller
{
    //
    public function authenticate(Request $request)
    {
        $user = $request->user();
        $pusher = app('pusher');

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $channel = $request->input('channel_name');
        $socketId = $request->input('socket_id');

        $auth = $pusher->socket_auth($channel, $socketId);

        return response()->json($auth);
    }
}
