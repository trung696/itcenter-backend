<?php

namespace App\Http\Middleware;

use App\SessionUser;
use Closure;
use Illuminate\Http\Request;

class checkTokenSend
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $tokenUp = $request->bearerToken();
        $checkIssetToken = SessionUser::where('token', $tokenUp)->first();
        if (empty($tokenUp)) {
            return response()->json([
                'status' => false,
                'heading' => 'không gửi token lên qua header à'
            ], 400);
        }elseif(empty($checkIssetToken)) {
            return response()->json([
                'status' => false,
                'heading' => 'token gửi lên không hợp lệ'
            ], 401);
        } else {
            return $next($request);
        }
    }
}
