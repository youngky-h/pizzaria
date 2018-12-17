<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\LogModSingleton;
use App\GlobalFunction\StringCollection;
use Illuminate\Support\Collection;


abstract class XController extends Controller
{
    //
    //
    protected $authMod;
    protected $logMod;
    protected $responseBuilder;
    protected $entity;

    public function __construct(){
    	$this->ucd = LogModSingleton::getInstance();
    	// $this->authMod = new AuthMod;
    }



}
