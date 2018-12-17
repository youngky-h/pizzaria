<?php

namespace App\Http\Middleware;

use Closure;
use UiCoreLog;

class ApiLogMiddleware
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
        $response = $next($request);
        $arrinput = ["platform", "version","data"];
        // dd($response->arrBack);

        // $dataType = gettype($response);
        // if ($dataType == 'string'){
        //     $msg='its string';
        // } else if($dataType == 'integer'){
        //     $msg='its an integer';
        // } else if ($dataType == 'object'){
        //     $msg=$response->getOriginalContent();
        // } 
        // else {
        //     $msg='what is it?';
        // }
        // // $responseData['test']=$msg;
        // $responseData['no']=100;
        $responseData=$response->getOriginalContent();
        $requestSend = UiCoreLog::getRequestJSON($request,$arrinput);
        $objData=json_decode($requestSend['data']);
        if(!empty($objData->photo)){
            $objData->photo = '-';
            $requestSend['data']=json_encode($objData);        
        }

        // json_decode($request['data']);
        $loggedResponse = $responseData;

        
        if(strpos($request->route()->getName(), 'fetch') > -1 && is_array($responseData)){
            $loggedResponse['data'] = '';
            // dd(['fksd', $loggedResponse]);
        }

        UiCoreLog::addLog($request->route()->getName(),"INFO",json_encode($requestSend), json_encode($loggedResponse), json_encode($request->get('sessionInfo')));

        // return response()->json($responseData,200);
        return $response;
    }
}
