<?php

namespace App\Http\Middleware;

use Closure;
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

            if($request->client_type == 'web' && !$miniProgram->hasUser(\Auth::id())) {
                return redirect('/admin/user');          
            }
            session(['xcx_id' => $miniProgram->id]);
            return $next($request);

        }else {

            if($request->client_type == 'web') {
                return redirect('/admin/user')->with('msg', '小程序不存在或没有权限！');;          
            }
            return response()->json(['msg' => '小程序ID错误! '])->setStatusCode(401);  

        }  
    }
}
