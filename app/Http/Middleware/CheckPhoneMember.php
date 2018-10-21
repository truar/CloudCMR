<?php

namespace App\Http\Middleware;

use Closure;

class CheckPhoneMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $phone = $request->phone;
        $member = $request->member;

        if($phone->member_id != $member->id) {
            return response()->json(['errors' => ['message' => 'The member ' . $member->id . ' has no phone ' . $phone->id]], 404);
        }

        return $next($request);
    }
}
