<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Services\Token;

class VerifyToken
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
        $token = $request->header('token');
        if(Token::verifyToken($token)){
            return $next($request);
        }
//        return response()->json(['msg' => 'token不存在或已过期! '])->setStatusCode(401);
        return redirect('admin/user/login');
    }
}
