<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Cookie;
use App\Models\UseAccount;
use Tymon\JWTAuth\Exceptions;
use App\Modules\AuthMod;
use UiCoreDebug as UCD;

class VerifyTokenMiddleware
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
        $sessionInfo = false;
        $statusCode = 1;
        if(Cookie::has('token')){
            $token = Cookie::get('token');
            AuthMod::prepareSession($token);
        } 
        
        if(AuthMod::getStaticOpStatus()<0){
            return redirect()->route('login')
                ->with("statusCode","-1")
                // ->with("message","Silahkan login."."Status: ".AuthMod::getStaticOpStatus())."<br>".UCD::getHtml('info');
                ->with("message","Silahkan login."."Status: ".AuthMod::getStaticOpStatus());
        }
            
        $guardian = AuthMod::getGuardian();  
        $sessionInfo = AuthMod::getSessionInfo();       
        
        $permissionName = $request->route()->getName();
        $canAccess = AuthMod::isCurrentUserCanAccess($permissionName);
        if(!$canAccess){
            return redirect()->route('auth_no_access')
                ->with('statusCode', '-100')
                ->with('message', 'Anda tidak mempunyai akses terhadap halaman ini.');            
        }        
        // dd(['stat'=>$statusCode,'user'=>$sessionInfo,'token'=>$token]);
        $request->attributes->add(['sessionInfo' => $sessionInfo,'guardian'=>$guardian]);
        return $next($request);
    }
}
