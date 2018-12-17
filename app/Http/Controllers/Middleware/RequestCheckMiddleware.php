<?php

namespace App\Http\Middleware;

use Closure;
use GlobalController;
use StringCollection;

class RequestCheckMiddleware
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
        $arrinput = ["platform", "version"];
        if (GlobalController::cekMaintenance()) {
            if (GlobalController::cekParameterInput($arrinput,$request)) {
                $urlVersion = GlobalController::cekVersion($request->get('platform'),$request->get('version'));
                if($urlVersion != ""){
                    $arrback['status_code'] = "-100";
                    $arrback['message'] = StringCollection::stringUpdate;
                }else{
                    return $next($request);
                }
            }else{
                $arrback['status_code'] = "-1";
                $arrback['message'] = StringCollection::stringParameterNotComplete;
            }
        }else{
            $arrback['status_code'] = "-200";
            $arrback['message'] = StringCollection::stringMaintenance;
        }
        $requestSend = GlobalController::getRequestJSON($request,$arrinput);
        GlobalController::addLog("FAIL ".$request->route()->getName(),json_encode($requestSend),json_encode($arrback),"Unknown");
        return response()->json($arrback, 200);
    }
}
