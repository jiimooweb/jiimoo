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
            cache([$token => cache($token)], config('token.token_expire_in'));  //刷新token时间
            return $next($request);
        }

        if($request->client_type == 'web') {
            return redirect('login')->with('msg', 'token不存在或已过期！');
        }

       return response()->json(['msg' => 'token不存在或已过期！'])->setStatusCode(401);
    }
}
