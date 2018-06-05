<?php 
namespace App\Http\Middleware;

use Closure;
use Response;

class Cors {

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {

    $response = $next($request);
    $response->headers->add([
      'Access-Control-Allow-Origin'=>'*',
      'Access-Control-Allow-Headers', 'Origin, Content-Type, Cookie, Accept',
      'Access-Control-Allow-Methods'=>'POST,GET,OPTIONS,PUT,DELETE,X-CSRF-TOKEN',
    ]);
    return $response;
  }

}