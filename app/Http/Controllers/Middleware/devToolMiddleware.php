<?php

namespace App\Http\Middleware;

use Closure;
use UiCoreDebug as UCD;
use App\Modules\IntraFlowControlMod;

class devToolMiddleware
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
        $ifc = IntraFlowControlMod::getInstance();
        $response = $next($request);
        if(env("APP_DEBUG",true)==true){
            $arResponse = $response->getOriginalContent();
            if(is_array($arResponse)){
                $arResponse['log'] = UCD::get('info');
                $arResponse['resultStack'] = $ifc->getResultStack();
                return response()->json($arResponse);
            }
            return $response;
        }
        return $response;
        // return response()->json($responseData,200);
    }
}
