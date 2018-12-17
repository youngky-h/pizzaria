<?php

namespace App\Http\Middleware;

use Closure;

use JWTAuth;
use Cookie;
use JWTException;
use StringCollection;
use UiCoreLog;
use App\Modules\AuthMod;
use App\Models\UseAccount;
use UiCoreDebug as UCD;

class ApiAuthCheckMiddleware
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
        $arrInput = ["platform", "version", "token"];
        $arrBack = array();
        // $statusToken = true;
        $token = $request->input('token');
        if ($token===null) {
            $arrBack = ['status_code' => -1, 'message' => 'Data permintaan tidak lengkap.'];
            return response()->json($arrBack);
        }
        AuthMod::prepareSession($token);
        //}//kalau sdh login
        $opStatus = AuthMod::getStaticOpStatus();
        $message = AuthMod::getStaticMessage(0);
        if ($opStatus < 0) {
            $arrBack = ['status_code' => $opStatus, 'message' => $message.' Silahkan login ulang.'];
            $requestSend = UiCoreLog::getRequestJSON($request, $arrInput);
            UiCoreLog::addLog($request->route()->getName(), "Not Authenticated", json_encode($requestSend), json_encode($arrBack), '-');
            return response()->json($arrBack);
        }
        //temporary disabled
        // $permissionName = $request->route()->getName();
        // $canAccess = AuthMod::isCurrentUserCanAccess($permissionName);
        // if(!$canAccess){
        //     $arrBack = ['status_code' => -1, 'message' => 'Anda tidak mempunyai wewenang untuk API ini.'];
        //     $requestSend = UiCoreLog::getRequestJSON($request, $arrInput);
        //     UiCoreLog::addLog($request->route()->getName(), "Not Authorized", json_encode($requestSend), json_encode($arrBack), '-');
        //     return response()->json($arrBack, 403);         
        // }                
        $request->attributes->add(['sessionInfo' => AuthMod::getSessionInfo()]);
        return $next($request);
    }
}
