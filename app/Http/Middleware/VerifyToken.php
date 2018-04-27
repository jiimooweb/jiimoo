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
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: token,Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: POST,GET');
        
        $token = $request->header('token');
        if(Token::verifyToken($token)){
            return $next($request);
        }
//        return response()->json(['msg' => 'token不存在或已过期! '])->setStatusCode(401);
        return redirect('admin/user/login');
    }
}
