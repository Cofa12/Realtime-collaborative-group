<?php

namespace App\Http\Middleware\Auth;

use App\Models\Group;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserExistInGroup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $is_exist = Group::where('user_id',$request->user()->id)->exists();
        if(!$is_exist){
            return response()->json([
                'status' => false,
                'message'=>'unautherized user'
            ],422);
        }
        return $next($request);
    }
}
