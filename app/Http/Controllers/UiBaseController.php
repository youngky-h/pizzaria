<?php

namespace App\Http\Controllers;

use UiCoreDebug as UCD;
use App\Modules\IntraFlowControlMod;

abstract class UiBaseController extends Controller
{
    //
    //
    protected $authMod;
    protected $logMod;
    protected $responseBuilder;
    protected $entity;
    protected $ifc;
    
    public function __construct(){
        $this->ucd = UCD::getInstance();
        $this->ifc = IntraFlowControlMod::getInstance();
        // $this->authMod = new AuthMod;
    }


    protected function dataPreparation($request, &$message='', &$statusCode, &$data = '', $arKey=[])
    {
        $reqData = json_decode($request->get('data'));
        $message = 'Struktur data yang dikirim tidak valid.';
        $statusCode = -2;

        if($reqData == null){
            return null;
        }

        if(!is_array($arKey)){
            return null;
        }
        
        $existMissingKey = false;
        foreach($arKey as $key){
            if(!isset($reqData->$key)){
                $existMissingKey = true;
                break;
            }
        }
        if($existMissingKey){
            return null;
        }
        return $reqData;
    }

    protected  function jsonBuilder($stat = -1, $msg = '', $data = '')
    {
    	// if(env('APP_DEBUG', false)){
    	// 	$arrResponse['log'] = UCD::get('info');
    	// }

        $arResponse['status_code'] = $stat;
		$arResponse['message'] = $msg;
		$arResponse['data'] =  $data;       
        return response()->json($arResponse);
    }
}
