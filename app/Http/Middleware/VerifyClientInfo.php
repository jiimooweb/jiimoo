<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\Token;
use App\Models\Commons\Xcx;

class VerifyClientInfo
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
        $miniProgram = Xcx::where('xcx_flag',$request->xcx_flag)->first();
        
        if($miniProgram) {
            if($request->client_type == 'web' && !$miniProgram->hasUser(Token::getUid())) {
                return redirect('login');          
            }
            session(['xcx_id' => $miniProgram->id]);
            session(['module' => $miniProgram->module]);
            return $next($request);

        }else {

            if($request->client_type == 'web') {
                return redirect('login')->with('msg', '小程序不存在或没有权限！');          
            }
            return response()->json(['msg' => '小程序ID错误! '])->setStatusCode(401);  

        }  
    }
    
}
