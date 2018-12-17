<?php

namespace App\Http\Middleware;

use Closure;
use UiCoreRequest;
use UiCoreLog;
use App\GlobalFunction\StringCollection;

class ApiRequestCheckMiddleware
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
        $arrback = array();
        if (UiCoreRequest::cekMaintenance()) {
            if (UiCoreRequest::cekParameterInput($arrinput,$request)) {
                $urlVersion = UiCoreRequest::cekVersion($request->get('platform'),$request->get('version'));
                if($urlVersion != ""){
                    $arrback['status_code'] = "-51";
                    $arrback['message'] = StringCollection::stringUpdate;
                }else{
                    return $next($request);
                }
            }else{
                $arrback['status_code'] = "-1";
                $arrback['message'] = StringCollection::stringParamterNotComplete;
            }
        }else{
            $arrback['status_code'] = "-52";
            $arrback['message'] = StringCollection::stringMaintenance;
        }
        $requestSend = UiCoreLog::getRequestJSON($request,$arrinput);
        UiCoreLog::addLog($request->url(),"FAIL1",json_encode($requestSend),json_encode($arrback),"Unknown");
        return response()->json($arrback, 200);
    }
}
