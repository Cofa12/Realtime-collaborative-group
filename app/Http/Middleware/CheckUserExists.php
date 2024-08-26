<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::wherein('id',[$request->route('id')])->exists();
        if(!$user){
            return response()->json([
                'status' => false,
                'message' => 'Not Found User'
            ],404);
        }
        return $next($request);
    }
}
