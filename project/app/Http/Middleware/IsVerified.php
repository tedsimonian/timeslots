<?php

namespace App\Http\Middleware;

use Closure;

class IsVerified
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


        if($request->user()->hasRole('user')){


            if(!$request->user()->verified){


                $request->session()->put('warning', 'Please verify your email in order to book appointments.');
                return $next($request);

            }else{
                $request->session()->forget('warning');

            }

        }


        return $next($request);
    }
}
