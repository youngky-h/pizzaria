<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cookie;
use Carbon\Carbon;
use GlobalController;
use App\Entity\CategoryEntity;
use App\Entity\CategoryPriceEntity;
use App\Entity\CompanyEnt;
use App\Entity\EmployeeEntity;
use App\Entity\InvoiceEnt;
use App\Entity\MutationEnt;
use App\Entity\ProductClassEntity;
use App\Entity\ProductEntity;
use App\Entity\StockEntity;
use App\Entity\StockEnt;
use App\Entity\StockOpnameCardEnt;
use App\Entity\StockOpnameActivityEnt;
use App\Entity\SubCategoryEntity;
use App\Entity\WidgetEntity;
use App\Entity\InvoiceEntity;
use App\Entity\PaymentEntity;
use App\Models\Mutation;
use App\Exceptions\BusinessException;
use App\Modules\AuthMod;
use App\Modules\BranchMod;
use App\Modules\CompanyMod;
use App\Modules\PaymentMod;
use App\Modules\ProductMgmtMod;
use App\Modules\SalesMod;
use App\Modules\StoreMod;
use App\Modules\StockMod;
use App\Repository\InvoiceRepo;
use App\Repository\CompanyRepo;
use App\Repository\RackRepo;
use App\Repository\MutationRepo;
use App\Repository\PaymentRepo;
use App\Repository\StockOpnameCardRepo;
use App\Repository\StockRepo;
use App\Repository\UserAccountRepo;
use App\Modules\LogModSingleton as logMod;
use App\Test\TestControl;
use Storage;
use UiCoreDebug as UCD;
use App\Http\Controllers\UiBaseController;
use App\Modules\IntraFlowControlMod;
use DB;

class DeveloperController extends UiBaseController
{

    public function __construct() {
        // $this->categoryEntity = new CategoryEntity;
        // $this->categoryPriceEntity = new CategoryPriceEntity;
        $this->stockEntity = new StockEntity;
        $this->subCategoryEntity = new SubCategoryEntity;
        $this->productClassEntity = new ProductClassEntity;
        $this->productEntity = new ProductEntity;
        $this->invoiceEntity = new InvoiceEntity;
        $this->paymentEntity = new PaymentEntity;
        $this->arResPrint = [];
        $this->arResDump = [
            'statusCode' => 0,
            'res' => null,
            'message' => 'No message.'
        ];
    }


    public function responder(Request $request){
        $ent = new SubCategoryEntity;
        $catId = $request->get('category_id');
        $data = $ent->getActiveEntity();
        //     return $obj->category_id==$catId;
        // });
        $arrResponse = [
            'status_code'=>1,
            'data'=>$data,
            'logs'=>logMod::get('warn')
        ];
        return response()->json($arrResponse);
    }

    public function redirectedPage(Request $request){


        return view('test_view')
            ->with([
                'res'=>$res,
                'qry'=>$qry,
                'nama'=>'xxx'
            ]);
    }

    // public function index(Request $request){

    //     \DB::enableQueryLog();
    //     $arResDump = [];
    //     $start = microtime(true);
    //     //start experimenting here
    //     // $this->authenticate();
    //     $this->testDbRollback($request);
    //     //end of experiment here
    //     $elapsedTime = microtime(true) - $start;
    //     $resPrint = collect([]);
    //     $qry = \DB::getQueryLog();
    //     $filteredQry = array_slice($qry, 0, 40);
    //     $this->arResDump['logs'] = UCD::get('info');
    //     $this->arResDump['elapsedTime'] = $elapsedTime;
    //     $this->arResDump['qry'] = $filteredQry;
    //     $this->arResDump['all_qry_count'] = count($qry);
    //     $this->arResDump['sessionInfo'] = $this->sessionInfo;

    //     return view('developer')
    //         ->with([
    //             'guardian'=>$request->get('guardian'),
    //             'resDump'=>$this->arResDump,
    //             'sessionInfo'=>$request->get('sessionInfo'),
    //             'resPrint'=>$resPrint,
    //             'nama'=>'xxx'
    //         ]);
    // }
    public function index(Request $request){

        //call tested function
        $arFunc = $this->testPaymentMod($request);
        $this->ucd = UCD::getInstance();
        $setupEnv = $arFunc['setup']();
        \DB::enableQueryLog();
        if($setupEnv){
            $start = microtime(true);
            $arFunc['run']($setupEnv);
            $elapsedTime = microtime(true) - $start;
            // dd(['fajisf', $setupEnv, gettype($this), get_class($this),$this]);
        }
        $qry = \DB::getQueryLog();
        $filteredQry = array_slice($qry, 0, 40);
        $this->arResDump['logs'] = UCD::get('info');
        $this->arResDump['elapsedTime'] = $elapsedTime;
        $this->arResDump['qry'] = $filteredQry;
        $this->arResDump['all_qry_count'] = count($qry);

        return view('developer')
            ->with([
                'guardian'=>$request->get('guardian'),
                'resDump'=>$this->arResDump,
                'resPrint'=>$this->arResPrint,
                'sessionInfo'=>$request->get('sessionInfo'),
                'nama'=>'xxx'
            ]);
    }

    public function authenticate($user, $pass)
    {
        $this->ifc = IntraFlowControlMod::getInstance();
        $token = AuthMod::authenticate($user, $pass);
        $lastEvent = $this->ifc->getLastEvent();
        if($lastEvent == 'srv.auth.login_success'){
            $prepSes = AuthMod::prepareSession($token);
            $this->sessionInfo = $this->ifc->getSesVar('sessionInfo');
            $this->guardian = $this->ifc->getSesVar('guardian');
            $this->arResDump['sessionInfo'] = $this->sessionInfo;
            return true;
        } else {
            $this->arResDump['statusCode'] = AuthMod::getStaticOpStatus();
            $this->arResDump['message'] = Authmod::getStaticMessage();
            return false;
        }
    }

    public function testPaymentMod($request)
    {
        return [
            'setup'=>function(){
                return [
                    'this'=>$this
                ];
            },
            'run'=>function($arSetup){
                $arSetup['this']->authenticate("Lina","1234");
                $obDateTime = Carbon::createFromFormat('d/m/Y H:i:s O', '10/12/2018 10:30:00 +7');
                $this->ifc->setDateTime($obDateTime);
                
                $stParam = '{"name":"kartu kredit bca", "charge_percentage":2.123,"payment_type_group":"credit-card", "desc":"okeh"}';
                $dtParam = json_decode($stParam);
                // dd($dtParam);
                $ptMod = new PaymentMod;
                $data = $ptMod->storePaymentType($dtParam);
                $statusCode = StockOpnameCardEnt::getStaticOpStatus();
                $message = StockOpnameCardEnt::getStaticMessage();

                $arSetup['this']->arResDump['res']['data'] = $data;
                $arSetup['this']->arResDump['res']['time'] = $this->ifc->getDateTime()->format('d/m/Y H:i:s O');
                $arSetup['this']->arResDump['statusCode'] = $statusCode;
                $arSetup['this']->arResDump['message'] = $message;
            }
        ];
    }

    public function testStockOpname1($request)
    {
        //base data db_with_sales
        //stateSnapshot name stock_opname_1
        return [
            'setup'=>function(){
                return true;
            },
            'run'=>function($arSetup){
                $authSucceed = $this->authenticate("Lina","1234");
                // dd(['asfopvw', $authSucceed, get_class($this)]);
                if(!isset($this->sessionInfo)){
                    return false;
                }
                $stockMod = new StockMod;

                // $obDateTime = Carbon::createFromFormat('Y-m-d H:i:s O', '2018-12-11 10:30:00 +7');
                // $this->ifc->setDateTime($obDateTime);

                // $stParam = '{"branch_id":2,"start_date":"2018-12-12", "end_date":"2018-12-13", "sm_id":2}';
                // $obParam = json_decode($stParam);
                // $data = $stockMod->storeSoa($obParam);
                //barcode 10020302 sys weight = 56.431, should be ok
                //barcode 10020357 sys weight = 100.058, should be detect as weight less
                //barcode 10020272 duplicate will fail one
                //barcode 77777777 not valid will fail  
                //barcode 10020395 fail , wrong soa id, will be consider missing
                //barcode 10020234 changed to 77720234 same weight
                //barcode 10020333 changed to 77720333 less weight
                //barcode 10020265, 10020296 , 10020364 and 10020258 missing

                $stParam = '[
                    {"barcode":"77777777","real_weight":200.755,"soa_id":6,"stock_state":1, "desc":"wrong location"},
                    {"barcode":"77720333","real_weight":200.755,"soa_id":6,"stock_state":0, "desc":"wrong location"},
                    {"barcode":"10020296","real_weight":200.995,"soa_id":6,"stock_state":0, "desc":"wrong product"},
                    {"barcode":"77720234","real_weight":62.035,"soa_id":6,"stock_state":0, "desc":"wrong location"},
                    {"barcode":"10020364","real_weight":129.884,"soa_id":6,"stock_state":0, "desc":"wrong product"},
                    {"barcode":"10020241","real_weight":134.174,"soa_id":6,"stock_state":1, "desc":"Yahooo."},
                    {"barcode":"10020319","real_weight":164.897,"soa_id":6,"stock_state":1, "desc":"Wow."},
                    {"barcode":"10020302","real_weight":56.4,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020357","real_weight":99.5,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020272","real_weight":70.051,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020272","real_weight":60.051,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020395","real_weight":109.995,"soa_id":7,"stock_state":1, "desc":""},
                    {"barcode":"10020326","real_weight":255.091,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020210","real_weight":233.442,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020227","real_weight":212.106,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020388","real_weight":287.909,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020289","real_weight":83.516,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020371","real_weight":188.858,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020340","real_weight":263.804,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020401","real_weight":177.147,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020425","real_weight":277.101,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020494","real_weight":297.172,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020524","real_weight":81.175,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020555","real_weight":294.46,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020609","real_weight":181.745,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020593","real_weight":187.662,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020449","real_weight":29.636,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020500","real_weight":233.476,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020579","real_weight":37.083,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020586","real_weight":158.318,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020470","real_weight":11.868,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020517","real_weight":34.217,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020548","real_weight":86.968,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020531","real_weight":40.653,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020463","real_weight":37.13,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020418","real_weight":10.747,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020432","real_weight":193.42,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020562","real_weight":97.034,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020456","real_weight":208.316,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020487","real_weight":144.682,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020616","real_weight":132.988,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020791","real_weight":296.42,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020722","real_weight":144.722,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020692","real_weight":257.432,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020739","real_weight":277.96,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020784","real_weight":272.104,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020746","real_weight":11.48,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020678","real_weight":82.97,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020654","real_weight":158.305,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020753","real_weight":147.044,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020807","real_weight":128.494,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020777","real_weight":39.984,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020623","real_weight":226.545,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020715","real_weight":295.809,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020708","real_weight":245.623,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020760","real_weight":275.33,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020630","real_weight":28.841,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020647","real_weight":83.1,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020685","real_weight":14.12,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020661","real_weight":105.543,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020968","real_weight":265.811,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020876","real_weight":109.79,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020852","real_weight":215.476,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020906","real_weight":50.257,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020951","real_weight":5.093,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020937","real_weight":269.859,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020883","real_weight":268.781,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020913","real_weight":120.111,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020890","real_weight":11.593,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020814","real_weight":153.042,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020944","real_weight":47.632,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020869","real_weight":242.353,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020982","real_weight":162.054,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020999","real_weight":200.422,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020920","real_weight":227.845,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020838","real_weight":8.504,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10021002","real_weight":208.59,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020821","real_weight":217,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020975","real_weight":149.92,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10020845","real_weight":192.774,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10021118","real_weight":15.491,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10021057","real_weight":85.198,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10021088","real_weight":97.764,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10021033","real_weight":75.147,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10021019","real_weight":252.352,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10021149","real_weight":279.847,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10021064","real_weight":219.528,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10021101","real_weight":76.35,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10021163","real_weight":253.604,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10021170","real_weight":8.615,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10021071","real_weight":206.116,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10021200","real_weight":7.378,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10021125","real_weight":179.891,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10021194","real_weight":30.265,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10021187","real_weight":50.73,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10021026","real_weight":117.359,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10021095","real_weight":295.971,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10021156","real_weight":15.605,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10021040","real_weight":215.58,"soa_id":6,"stock_state":1, "desc":""},
                    {"barcode":"10021132","real_weight":147.985,"soa_id":6,"stock_state":1, "desc":""}
                ]';

                $arobParam = json_decode($stParam);
                $this->arResDump['res'] = [];
                foreach($arobParam as $obParam){
                    $this->ucd->clearLogs();
                    $adjParam = new \StdClass;
                    $adjParam->main = $obParam;
                    $obParam->user_id = $this->sessionInfo['userId'];
                    $data = $stockMod->storeCard($adjParam);
                    $opStatus = $stockMod->getOpStatus();
                    $message = $stockMod->getMessage(0);
                    $log = $this->ucd->getLogs('info');
                    if($opStatus < 0){
                        $resGroup = 'fail';
                    } else {
                        $resGroup = 'success';
                    }
                    $this->arResDump['res'][$resGroup][] = [
                        'data'=>$data,
                        'opStatus'=>$opStatus,
                        'message'=>$message,
                        'log'=>$log
                    ];
                }

                $this->arResDump['res']['time'] = $this->ifc->getDateTime()->format('d/m/Y H:i:s O');
                $this->arResDump['opStatus'] = $stockMod->getOpStatus();
                $this->arResDump['message'] = $stockMod->getMessage(0);
                return true;
            }
        ];
    }

    public function testStockEnt1($request)
    {
        return [
            'setup'=>function(){
                return [
                    'this'=>$this
                ];
            },
            'run'=>function($arSetup){
                $arSetup['this']->authenticate("Lina","1234");
                $obDateTime = Carbon::createFromFormat('d/m/Y H:i:s O', '10/12/2018 10:30:00 +7');
                $this->ifc->setDateTime($obDateTime);
                
                $stParam = '{"user_id":36, "barcode":"10020302","real_weight":147.985,"soa_id":6,"stock_state":1, "desc":"-"}';
                $obParam = json_decode($stParam);
                $dtParam = new \StdClass;
                $dtParam->main = $obParam;
                // dd($dtParam);
                $data = StockOpnameCardEnt::create($dtParam);
                $statusCode = StockOpnameCardEnt::getStaticOpStatus();
                $message = StockOpnameCardEnt::getStaticMessage();

                $arSetup['this']->arResDump['res']['data'] = $data;
                $arSetup['this']->arResDump['res']['time'] = $this->ifc->getDateTime()->format('d/m/Y H:i:s O');
                $arSetup['this']->arResDump['statusCode'] = $statusCode;
                $arSetup['this']->arResDump['message'] = $message;
            }
        ];
    }

    public function testSalesMod($request)
    {
        return [
            'setup'=>function(){
                return [
                    'this'=>$this
                ];
            },
            'run'=>function($arSetup){
                $arSetup['this']->authenticate("Damien","1234");
                $obDateTime = Carbon::createFromFormat('d/m/Y H:i:s O', '10/12/2018 10:30:00 +7');
                $this->ifc->setDateTime($obDateTime);
                
                $salesMod = new SalesMod;
                $stParam = '{"invoice":{"main": {"employee_id":17, "customer_id":4, "prev_invoice_id":0},"invoice_detail": [{"product_id":227}]}, "payment": [{"payment_type":"cash", "card_number":"","bank_id":0, "change":1000, "amount":1414000, "card_charge":0}, {"payment_type":"debit-card", "card_number":"22202343", "bank_id":1, "amount":80000000, "change":0, "card_charge":80000}]}';
                $obParam = json_decode($stParam);
                $data = $salesMod->storeSales($obParam);
                $statusCode = $salesMod->getOpStatus();
                $message = $salesMod->getMessage();

                $arSetup['this']->arResDump['res']['data'] = $data;
                $arSetup['this']->arResDump['res']['time'] = $this->ifc->getDateTime()->format('d/m/Y H:i:s O');
                $arSetup['this']->arResDump['statusCode'] = $statusCode;
                $arSetup['this']->arResDump['message'] = $message;
            }
        ];
    }

    public function testStockMutation1($request)
    {
        //base data salesreport01>sales_approved = db_with_sales

    }

    public function testSalesReport01($request)
    {
        return [
            'setup'=>function(){
                return [
                    'this'=>$this,
                ];            
            },
            'run'=>function($arSetup){
                $arSetup['this']->authenticate("Damien","1234");

                $transDate = Carbon::createFromFormat('d/m/Y H:i:s O','1/12/2018 10:00:30 +7');                
                $stParam = '[{"invoice":{"main": {"employee_id":16, "customer_id":1, "prev_invoice_id":0},"invoice_detail": [{"product_id":126}]}, "payment": [{"payment_type":"cash", "card_number":"","bank_id":0, "change":2000, "amount":158000, "card_charge":0}, {"payment_type":"debit-card", "card_number":"22202343", "bank_id":1, "amount":80000000, "change":0, "card_charge":80000}]}, 
                {"invoice":{"main": {"employee_id":17, "customer_id":2, "prev_invoice_id":0},"invoice_detail": [{"product_id":156}]}, "payment": [{"payment_type":"cash", "card_number":"","bank_id":0, "change":1000, "amount":3599000, "card_charge":0}, {"payment_type":"debit-card", "card_number":"22202343", "bank_id":1, "amount":10000000, "change":0, "card_charge":10000}]},
                {"invoice":{"main": {"employee_id":19, "customer_id":3, "prev_invoice_id":0},"invoice_detail": [{"product_id":144}]}, "payment": [{"payment_type":"cash", "card_number":"","bank_id":0, "change":4000, "amount":6596000, "card_charge":0}, {"payment_type":"debit-card", "card_number":"22202343", "bank_id":1, "amount":50000000, "change":0, "card_charge":50000}]}]';
                $arResult[] = $arSetup['this']->execSales($stParam, $transDate);

                $transDate = Carbon::createFromFormat('d/m/Y H:i:s O','2/12/2018 10:00:30 +7');
                $stParam = '[
                {"invoice":{"main": {"employee_id":23, "customer_id":4, "prev_invoice_id":0},"invoice_detail": [{"product_id":146}]}, "payment": [{"payment_type":"cash", "card_number":"","bank_id":0, "change":0, "amount":265000, "card_charge":0}, {"payment_type":"debit-card", "card_number":"22202343", "bank_id":1, "amount":76000000, "change":0, "card_charge":1400000}]},
                {"invoice":{"main": {"employee_id":25, "customer_id":5, "prev_invoice_id":0},"invoice_detail": [{"product_id":143}]}, "payment": [{"payment_type":"cash", "card_number":"","bank_id":0, "change":20000, "amount":930000, "card_charge":0}, {"payment_type":"debit-card", "card_number":"22202343", "bank_id":1, "amount":18000000, "change":0, "card_charge":180000}]},
                {"invoice":{"main": {"employee_id":26, "customer_id":6, "prev_invoice_id":0},"invoice_detail": [{"product_id":180}]}, "payment": [{"payment_type":"cash", "card_number":"","bank_id":0, "change":0, "amount":461000, "card_charge":0}, {"payment_type":"debit-card", "card_number":"22202343", "bank_id":1, "amount":8000000, "change":0, "card_charge":80000}]},
                {"invoice":{"main": {"employee_id":27, "customer_id":7, "prev_invoice_id":0},"invoice_detail": [{"product_id":222}]}, "payment": [{"payment_type":"cash", "card_number":"","bank_id":0, "change":4000, "amount":786000, "card_charge":0}, {"payment_type":"debit-card", "card_number":"22202343", "bank_id":1, "amount":17000000, "change":0, "card_charge":170000}]},
                {"invoice":{"main": {"employee_id":16, "customer_id":8, "prev_invoice_id":0},"invoice_detail": [{"product_id":232}]}, "payment": [{"payment_type":"cash", "card_number":"","bank_id":0, "change":2000, "amount":138000, "card_charge":0}, {"payment_type":"debit-card", "card_number":"22202343", "bank_id":1, "amount":11000000, "change":0, "card_charge":110000}]},
                {"invoice":{"main": {"employee_id":17, "customer_id":9, "prev_invoice_id":0},"invoice_detail": [{"product_id":238}]}, "payment": [{"payment_type":"cash", "card_number":"","bank_id":0, "change":40000, "amount":960000, "card_charge":0}, {"payment_type":"debit-card", "card_number":"22202343", "bank_id":1, "amount":13000000, "change":0, "card_charge":130000}]}]';
                $arResult[] = $arSetup['this']->execSales($stParam, $transDate);

                $transDate = Carbon::createFromFormat('d/m/Y H:i:s O','3/12/2018 10:00:30 +7');
                $stParam = '[
                {"invoice":{"main": {"employee_id":19, "customer_id":10, "prev_invoice_id":0},"invoice_detail": [{"product_id":247}]}, "payment": [{"payment_type":"cash", "card_number":"","bank_id":0, "change":3000, "amount":832000, "card_charge":0}, {"payment_type":"credit-card", "card_number":"123423423", "bank_id":2, "amount":80000000, "change":0, "card_charge":800000}]},
                {"invoice":{"main": {"employee_id":23, "customer_id":1, "prev_invoice_id":0},"invoice_detail": [{"product_id":264}]}, "payment": [{"payment_type":"cash", "card_number":"","bank_id":0, "change":1000, "amount":834000, "card_charge":0}, {"payment_type":"credit-card", "card_number":"123423423", "bank_id":2, "amount":67000000, "change":0, "card_charge":670000}]},
                {"invoice":{"main": {"employee_id":25, "customer_id":2, "prev_invoice_id":0},"invoice_detail": [{"product_id":326}]}, "payment": [{"payment_type":"cash", "card_number":"","bank_id":0, "change":4000, "amount":456000, "card_charge":0}, {"payment_type":"credit-card", "card_number":"123423423", "bank_id":2, "amount":33000000, "change":0, "card_charge":330000}]},
                {"invoice":{"main": {"employee_id":26, "customer_id":3, "prev_invoice_id":0},"invoice_detail": [{"product_id":347}]}, "payment": [{"payment_type":"cash", "card_number":"","bank_id":0, "change":4000, "amount":136000, "card_charge":0}, {"payment_type":"credit-card", "card_number":"123423423", "bank_id":2, "amount":91000000, "change":0, "card_charge":910000}]},
                {"invoice":{"main": {"employee_id":27, "customer_id":4, "prev_invoice_id":0},"invoice_detail": [{"product_id":364}]}, "payment": [{"payment_type":"cash", "card_number":"","bank_id":0, "change":6000, "amount":44000, "card_charge":0}, {"payment_type":"credit-card", "card_number":"123423423", "bank_id":2, "amount":5000000, "change":0, "card_charge":50000}]},                
                {"invoice":{"main": {"employee_id":16, "customer_id":5, "prev_invoice_id":0},"invoice_detail": [{"product_id":393}]}, "payment": [{"payment_type":"cash", "card_number":"","bank_id":0, "change":0, "amount":400000, "card_charge":0}, {"payment_type":"credit-card", "card_number":"123423423", "bank_id":2, "amount":14000000, "change":0, "card_charge":140000}]},
                {"invoice":{"main": {"employee_id":17, "customer_id":6, "prev_invoice_id":0},"invoice_detail": [{"product_id":414}]}, "payment": [{"payment_type":"cash", "card_number":"","bank_id":0, "change":2000, "amount":278000, "card_charge":0}, {"payment_type":"credit-card", "card_number":"123423423", "bank_id":2, "amount":26000000, "change":0, "card_charge":260000}]}
                ]';
                $arResult[] = $arSetup['this']->execSales($stParam, $transDate);

                $arSetup['this']->arResDump['res'] = $arResult;
            }
        ];
    }
    public function execSales($stParam, $transDate)
    {

        $salesMod = new SalesMod;
        $this->ifc->setDateTime($transDate);
        $arParam = json_decode($stParam);
        if($arParam===null){
            dd('fail');
        }
        // dd($arParam);
        $data = [];
        // dd([getcwd(), __DIR__]);
        // $fp = fopen('/var/www/goudenpos_v2/app/Test/product.csv', 'w');
        $itr = 0;
        foreach($arParam as $param){
            // dd($param);
            UCD::clear();
            UCD::add('info', "inv no $itr.");
            $itr++;
            $row = $salesMod->storeSales($param);
            $opStatus =  $salesMod->getOpStatus();
            $message = $salesMod->getMessage(0);
            if($opStatus<0){
                break;
            }
            $data[] = [$row, $opStatus, $message];
            // fputcsv($fp, $row, ',');

        }
        // fclose($fp);
        return $data;

    }
    public function testStockRepo($request)
    {
        return [
            'setup'=>function(){
                return [
                    'this'=>$this,
                ];            
            },
            'run'=>function($arSetup){
                $arSetup['this']->authenticate("Damien","1234");
                $stockRepo = new StockRepo;
                $data = $stockRepo->fetchDtoById(126, ['mode'=>'extended']);
                $statusCode = $stockRepo->getOpStatus();
                $message = $stockRepo->getMessage();

                $arSetup['this']->arResDump['res']['data'] = $data;
                $arSetup['this']->arResDump['statusCode'] = $statusCode;
                $arSetup['this']->arResDump['message'] = $message;
            }
        ];
    }


    public function testSales04($request)
    {
        //test accept sales 
        //case1: accept sales with cancelled prev inv
        //case2: accept sales with normal prev inv
        //case3: cancel inv with prev inv
        //case4: reject invoice
        //db: 00, 
        return [
            'setup'=>function(){
                $caseCode = 'sales04';
                $tc = new TestControl;
                // $tc->loadStateSnapshot('sales_created','sales04');
                return [
                    'caseCode'=>$caseCode,
                    'tc'=>$tc,
                    'this'=>$this
                ];

            },
            'run'=>function($arSetup){
                $arSetup['this']->authenticate("Damien","1234");
                $salesMod = new SalesMod;
                $stParam = '{"action_target_id":13,"action_name":"cancel-sales","inv_no":"-"}';
                $obParam = json_decode($stParam);
                // dd('sdfasd');
                $data = $salesMod->acceptSales($obParam->action_target_id, $obParam->inv_no);
                $statusCode = $salesMod->getOpStatus();
                $message = $salesMod->getMessage();

                $arSetup['this']->arResDump['res']['data'] = $data;
                $arSetup['this']->arResDump['statusCode'] = $statusCode;
                $arSetup['this']->arResDump['message'] = $message;
            }
        ];
    }

    public function testSales03($request)
    {
        //test cancel sales
        //db: 00, 
        return [
            'setup'=>function(){
                $caseCode = 'sales03';
                $tc = new TestControl;
                $tc->loadStateSnapshot('sales_approved','sales03');
                return [
                    'caseCode'=>$caseCode,
                    'tc'=>$tc,
                    'this'=>$this
                ];

            },
            'run'=>function($arSetup){
                $arSetup['this']->authenticate("Damien","1234");
                $salesMod = new SalesMod;
                $stParam = '{"action_target_id":13,"action_name":"cancel-sales","inv_no":"-"}';
                $obParam = json_decode($stParam);
                // dd('sdfasd');
                $data = $salesMod->cancelSales($obParam->action_target_id);
                $statusCode = $salesMod->getOpStatus();
                $message = $salesMod->getMessage();

                $arSetup['this']->arResDump['res']['data'] = $data;
                $arSetup['this']->arResDump['statusCode'] = $statusCode;
                $arSetup['this']->arResDump['message'] = $message;
            }
        ];
    }

    public function testSales02($request)
    {
        //test rejecting sales
        //db: 00, before sales approved-crSales01
        $caseCode = 'testSales02';
        $this->authenticate("Damien","1234");
        $salesMod = new SalesMod;
        $stParam = '{"invoice":{"main":{"employee_id":15,"customer_id":2, "prev_invoice_id":8},"invoice_detail":[{"product_id":482}]}, "payment": []}';
        $obParam = json_decode($stParam);
        $data = $salesMod->storeSales($obParam);
        $statusCode = $salesMod->getOpStatus();
        $message = $salesMod->getMessage(0);
        $tc->saveStateSnapshot('sales_created', $caseCode);

        $this->arResDump['res']['data'] = $data;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;

    }
    public function testCrSales01($request)
    {
        //test sales using prev invoice id from same company
        //Login: Damien, franc co=2
        //Employee Rogelio 15, south micelaton = 3
        //sales 8, prod id 573, harga bersih 94.229.000 ,company franc
        $caseCode = 'crSales01';
        $tc = new TestControl;
        $this->authenticate("Damien","1234");
        $tc->saveStateSnapshot('before_sales_created',$caseCode);
        $salesMod = new SalesMod;
        $stParam = '{"invoice":{"main":{"employee_id":15,"customer_id":2, "prev_invoice_id":8},"invoice_detail":[{"product_id":482}]}, "payment": []}';
        $obParam = json_decode($stParam);
        $data = $salesMod->storeSales($obParam);
        $statusCode = $salesMod->getOpStatus();
        $message = $salesMod->getMessage(0);
        $tc->saveStateSnapshot('sales_created', $caseCode);
        // if($statusCode > 0){
        //     $data = $salesMod(accept)
        // }

        $this->arResDump['res']['data'] = $data;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;

    }


    public function testRack($request)
    {
        $this->authenticate();
        $rpRack = new RackRepo;
        $dtRackWarehouse = $rpRack->fetchColDtoEnabledByBranchId(15);
        $statusCode = $rpRack->getOpStatus();
        $message = $rpRack->getMessage(0);

        $this->arResDump['res'] = $dtRackWarehouse;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;        
    }

    public function testCancelSales($request)
    {
        $salesMod = new SalesMod;
        $data = $salesMod->cancelSales(88);
        $statusCode = $salesMod->getOpStatus();
        $message = $salesMod->getMessage();

        $this->arResDump['res']['data'] = $data;
        $this->arResDump['res']['sessionInfo'] = $this->sessionInfo;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;

    }

    public function testAcceptSales($request)
    {
        $salesMod = new SalesMod;
        $data = $salesMod->acceptSales(88, '12345');
        $statusCode = $salesMod->getOpStatus();
        $message = $salesMod->getMessage();

        $this->arResDump['res']['data'] = $data;
        $this->arResDump['res']['sessionInfo'] = $this->sessionInfo;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;

    }


    public function testBranchMod($request)
    {
        $this->authenticate();
        $branchMod = new BranchMod;
        $data = $branchMod->fetchListEnabled();
        $statusCode = $branchMod->getOpStatus();
        $message = $branchMod->getMessage();

        $this->arResDump['res']['data'] = $data;
        $this->arResDump['res']['sessionInfo'] = $this->sessionInfo;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;
    }

    public function testCompanyMod($request)
    {
        $this->authenticate();
        $companyMod = new CompanyMod;
        $data = $companyMod->fetchListEnabled();
        $statusCode = $companyMod->getOpStatus();
        $message = $companyMod->getMessage();

        $this->arResDump['res']['data'] = $data;
        $this->arResDump['res']['sessionInfo'] = $this->sessionInfo;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;
    }


    public function testProductMgmtMod($request)
    {
        $res['token'] = AuthMod::authenticate('nobis', '1234');
        $lastEvent = $this->ifc->getLastEvent();
        if($lastEvent == 'srv.auth.login_success'){
            $res['prepses'] = AuthMod::prepareSession($res['token']);
            $res['sesinfo'] = $this->ifc->getSesVar('sessionInfo');
            $res['guardian'] = $this->ifc->getSesVar('guardian');
        }
        $qry = ProductMgmtMod::getActiveProductStockQuery();
        $res['data'] = $qry->first();

        $message = $this->ifc->getLastUserMessage();
        $statusCode = $this->ifc->getLastOpStatus();
        // $res = AuthMod::prepareSession($)
        $this->arResDump['res'] = $res;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;
        $this->arResDump['processStack'] = $this->ifc->getResultStack();

    }

    public function testAuthMod($request)
    {
        $res['token'] = AuthMod::authenticate('Erick', '12345');
        $lastEvent = $this->ifc->getLastEvent();
        if($lastEvent == 'srv.auth.login_success'){
            $res['prepses'] = AuthMod::prepareSession($res['token']);
            $res['sesinfo'] = $this->ifc->getSesVar('sessionInfo');
            $res['guardian'] = $this->ifc->getSesVar('guardian');
        }
        $empEnt = new EmployeeEntity;
        $res['active empl'] = $empEnt->getActiveEmployee();

        $message = $this->ifc->getLastUserMessage();
        $statusCode = $this->ifc->getLastOpStatus();
        // $res = AuthMod::prepareSession($)
        $this->arResDump['res'] = $res;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;
        $this->arResDump['processStack'] = $this->ifc->getResultStack();
    }

    public function testCompanyEnt($request)
    {
        try{
            $dtCompany = (object)[ 'main' => (object)[
            // $dtCompany = (object)[
                    'name' => 'Golden John',
                    'address' => 'Sunset Boulevard 1012',
                    'phone' => '1023322',
                    'email' => 'info@goldenjohn.com',
                    'owner' => 'john',
                    'max_user' => '34'
                ]
            ];
            $res = null;
            $rpCompany = new CompanyRepo;
            // $enCompany = CompanyEnt::create($dtCompany);
            // $res = $rpCompany->storeEntity($enCompany);

            $dtCompany = $rpCompany->fetchDtoById(18);
            $enCompany = CompanyEnt::hydrate($dtCompany);
            $enCompany->delete();
            $enCompany->enable();
            // dd($enCompany->getDto());
            $rpCompany->storeEntity($enCompany);
            $this->arResDump['message'] = $this->ifc->getLastUserMessage();
        } catch (BusinessException $bsnException){
            $this->arResDump['message'] = 'BusinessException thrown. '.$this->ifc->getLastUserMessage();
        } finally {
            $this->arResDump['statusCode'] = $this->ifc->getLastOpStatus();
            $this->arResDump['res'] = $res;
            $this->arResDump['processStack'] = $this->ifc->getResultStack();
        }

    }

    public function testArrayFunc($request){
        $x = [
            'add'=>function($a, $b){
                $message = "adding $a and $b";
                $calc = $a + $b;
                return [$calc, $message];
            },
            'sub'=>function($a, $b){
                $message = "subtract $a with $b";
                $calc = $a - $b;
                return [$calc, $message];
            },
            'square'=>function($a){
                $message = "squaring $a";
                $calc = $a * $a;
                return [$calc, $message];
            }
        ];
        $res = $x['square'](3,5);
        $statusCode = 1;
        $this->arResDump['res'] = $res[0];
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $res[1];

    }

    public function testCompanyUpdate($request)
    {
        $companyMod = new CompanyMod;
        $obParam = (object)['id'=>21,'name'=>'Adrian Jewelerry', 'phone'=>'021-121212', 'email'=>'adrian@myjewellery.com'];
        $res = $companyMod->update($obParam);
        $statusCode = $companyMod->getOpStatus();
        $message = $companyMod->getMessage();

        $this->arResDump['res'] = $res;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;
        $this->arResDump['processStack'] = $this->ifc->getResultStack();

    }

    public function testCompanyAction($request)
    {
        $companyMod = new CompanyMod;
        $res = $companyMod->action('disable',3);
        $statusCode = $companyMod->getOpStatus();
        $message = $companyMod->getMessage();

        $this->arResDump['res'] = $res;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;
        $this->arResDump['processStack'] = $this->ifc->getResultStack();

    }

    public function testCompanyFetchById($request)
    {
        $companyMod = new CompanyMod;
        $res = $companyMod->fetchById(18);
        $statusCode = $companyMod->getOpStatus();
        $message = $companyMod->getMessage();

        $this->arResDump['res'] = $res;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;
        $this->arResDump['processStack'] = $this->ifc->getResultStack();

    }

    public function testCompanyListAvailable($request)
    {
        $companyMod = new CompanyMod;
        $res = $companyMod->fetchListAvailable();
        $statusCode = $companyMod->getOpStatus();
        $message = $companyMod->getMessage();

        $this->arResDump['res'] = $res;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;
        $this->arResDump['processStack'] = $this->ifc->getResultStack();

    }

    public function testCompanyCreate($request)
    {
        $companyMod = new CompanyMod;
        $obParam = (object)[
            'name'=>'Franc & Co',
            'address'=>"Hr. Muh. 100",
            'phone'=>'031234324',
            'email'=>'franc_n_co@franc-company.com',
            'owner'=>'David Abaghnale',
            'max_user'=>100
        ];
        $res = $companyMod->create($obParam);
        $statusCode = $companyMod->getOpStatus();
        $message = $companyMod->getMessage();

        $this->arResDump['res'] = $res;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;
        $this->arResDump['processStack'] = $this->ifc->getResultStack();

    }

    public function testFetchListEnabledPayment($request)
    {
        $rpPayment = new PaymentRepo;
        $dtPayment = $rpPayment->fetchColDtoEnabledByInvoiceId(6334);
        $statusCode = $rpPayment->getOpStatus();
        $message = $rpPayment->getMessage();

        $this->arResDump['res'] = $dtPayment;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;

    }

    public function testFetchInvoiceById($request)
    {
        $rpInvoice = new InvoiceRepo;
        $dtInvoice = $rpInvoice->fetchDtoById(6333);
        $statusCode = $rpInvoice->getOpStatus();
        $message = $rpInvoice->getMessage();

        $this->arResDump['res'] = $dtInvoice;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;

    }

    public function testDeleteSmReport($request)
    {
        $stockMod = new StockMod;
        $res = $stockMod->deleteSmReport(1);
        $statusCode = $stockMod->getOpStatus();
        $message = $stockMod->getMessage();

        $this->arResDump['res'] = $res;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;

    }

    public function testMutateStock($request)
    {
        $stockMod = new StockMod;
        $res = $stockMod->returnStockToWarehouse('sdf');
        $statusCode = $stockMod->getOpStatus();
        $message = $stockMod->getMessage();

        $this->arResDump['res'] = $res;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;

    }

    public function testMutation($request)
    {
        // $rpStock = new StockRepo;
        // $dtStock = $rpStock->fetchDtoById(100);
        // $enStock = StockEnt::hydrate($dtStock);

        $obParam = new \StdClass;
        $obParam->main = new \StdClass;
        $obParam->main->mutation_type = 'to-warehouse';
        $obParam->main->src_rack_id = 14;
        $obParam->main->dst_rack_id = 2;
        $obParam->main->stock_id = 2;

        $enMutation = MutationEnt::create($obParam);
        $rpMutation = new MutationRepo;
        $rpMutation->storeEntity($enMutation);
        $statusCode = MutationEnt::getStaticOpStatus();
        $message = MutationEnt::getStaticMessage(0);

        $this->arResDump['res'] = $enMutation;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;

    }

    public function testReturnToWarehouse($request)
    {
        $stockMod = new StockMod;
        $res = $stockMod->returnStockToWarehouse([276,664]);
        $statusCode = $stockMod->getOpStatus();
        $message = $stockMod->getMessage(0);
        $this->arResDump['res'] = $res;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;

    }

    public function testFinishSoa($request)
    {
        $this->stockMod = new StockMod;
        $reqData = new \StdClass;
        $reqData->soa_id = 4;
        $res = $this->stockMod->finishSoa($reqData->soa_id);
        $statusCode = $this->stockMod->getOpStatus();
        $message = $this->stockMod->getMessage(0);
        $this->arResDump['res'] = array_slice((array)$res,0,1);
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;
    }

    public function testFetchSoReportById($request)
    {
        $this->stockMod = new StockMod;
        $reqData = new \StdClass;
        $reqData->soa_id = 1;
        $res = $this->stockMod->fetchSoReportById($reqData->soa_id);
        $statusCode = $this->stockMod->getOpStatus();
        $message = $this->stockMod->getMessage(0);
        // dd([$res,count($res)]);
        $this->arResDump['res'] = $res->{0};
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;

    }

    public function testCompileStockOpnameReport($request)
    {
        $this->stockMod = new StockMod;
        $reqData = new \StdClass;
        $reqData->soa_id = 1;
        $res = $this->stockMod->compileSoReport($reqData->soa_id);
        $statusCode = $this->stockMod->getOpStatus();
        $message = $this->stockMod->getMessage(0);
        $this->arResDump['res'] = $res;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;
    }


    public function startSoa($request)
    {
        $this->stockMod = new StockMod;
        $reqData = new \StdClass;
        $reqData->soa_id = 4;
        $res = $this->stockMod->startSoa($reqData->soa_id);
        $statusCode = $this->stockMod->getOpStatus();
        $message = $this->stockMod->getMessage(0);
        $this->arResDump['res'] = array_slice((array)$res,0,1);
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;

    }


    public function changeSoaState($request)
    {
        $stockMod = new StockMod;

        $res = $stockMod->changeSoaState(3, 'started');
        $statusCode = $stockMod->getOpStatus();
        $message = $stockMod->getMessage(0);


        $this->arResDump['res'] = $res;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;

    }

    public function setSmForSoa($request)
    {
        $stockMod = new StockMod;
        $obParam = new \StdClass;
        $obParam->sm_id = 2;
        $obParam->soa_id = 1;

        $res = $stockMod->setSmForSoa($obParam);
        $statusCode = $stockMod->getOpStatus();
        $message = $stockMod->getMessage(0);
        $this->arResDump['res'] = $res;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;
    }


    public function testFetchSoaById($request)
    {
        $stockMod = new StockMod;
        $res = $stockMod->fetchSoaById(4);
        $statusCode = $stockMod->getOpStatus();
        $message = $stockMod->getMessage(0);
        $this->arResDump['res'] = $res;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;
    }

    public function testFetchListAvailableSoa($request)
    {
        $stockMod = new StockMod;
        $res = $stockMod->fetchListAvailableSoa();
        $statusCode = $stockMod->getOpStatus();
        $message = $stockMod->getMessage(0);
        $this->arResDump['res'] = $res;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;
    }

    public function storeSoa($request)
    {
        $dto = new \StdClass;
        $dto = (object) [
            'start_date'=>'2018-09-13',
            'end_date'=>'2018-09-14',
            'branch_id'=>2,
            'sm_id'=>1
        ];
        $stockMod = new StockMod;
        $res = $stockMod->storeSoa($dto);
        $statusCode = $stockMod->getOpStatus();
        $message = $stockMod->getMessage(0);
        $this->arResDump['res'] = $res;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;

    }

    public function testFetchSmReportById($request)
    {
        $stockMod = new StockMod;
        $this->arResDump['res'] = $stockMod->fetchSmReportById(15);
        $this->arResDump['statusCode'] = $stockMod->getOpStatus();
        $this->arResDump['message'] = $stockMod->getMessage(0);
    }

    public function testFetchListAvailableSmReport($request)
    {
        $stockMod = new StockMod;
        $this->arResDump['res'] = $stockMod->fetchListAvailableSmReport();
        $this->arResDump['statusCode'] = $stockMod->getOpStatus();
        $this->arResDump['message'] = $stockMod->getMessage(0);
    }

    public function testCompileStockManifestReport($request)
    {
        $stockMod = new StockMod;
        $res = $stockMod->compileSmReport(2);
        $statusCode = $stockMod->getOpStatus();
        $message = $stockMod->getMessage(0);

        $this->arResDump['res'] = $res;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;

    }

    public function testFetchListAvailableRackOnBranch($request)
    {
        $branchId = 2;
        $storeMod = new StoreMod;
        $res = $storeMod->fetchListAvailableRackOnBranch($branchId);
        $statusCode = $storeMod->getOpStatus();
        $message = $storeMod->getMessage(0);
        $this->arResDump['res'] = $res;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;
    }


    public function testListAvailableSoc($request)
    {
        $sessionInfo = $request->get('sessionInfo');
        $stockMod = new StockMod;
        $res = $stockMod->fetchListAvailableSoc();
        $opStatus = $stockMod->getOpStatus();
        $message = $stockMod->getMessage();
        $this->arResDump['res'] = $res;
        $this->arResDump['opStatus'] = $opStatus;
        $this->arResDump['message'] = $message;
    }

    public function testStockOpnameCardStore($request)
    {
        $dto = new \StdClass;
        $dto->main = (object) [
            'barcode'=>'1001210013',
            'real_weight'=>34.45,
            'exist'=>2,
            'user_id'=>3,
            'soa_id'=>4
        ];
        $stockMod = new StockMod;
        $res = $stockMod->storeCard($dto);
        $statusCode = $stockMod->getOpStatus();
        $message = $stockMod->getMessage(0);
        $this->arResDump['res'] = $res;
        $this->arResDump['statusCode'] = $statusCode;
        $this->arResDump['message'] = $message;
    }

    public function testSessionInfo(Request $request)
    {
        $sessionInfo = $request->get('sessionInfo');
        $this->arResDump['sessionInfo'] = $sessionInfo;
    }

    protected function testLaporanPenambahanStokToko()
    {
        $enMut = new MutationEntity;
        $par = ['type'=>'to-store', 'mutationDate'=>'2018-05-21', 'branchId'=>2];
        $arMutation = $enMut->getMutationOnBranch($par);
    }


    // public function (){

    // };

    public function testBuildSalesReport2(){
        $ih = new App\Models\InvoiceHeader;

        $arrPar = [
            'start_date'=>'2018-04-01',
            'end_date'=>'2018-04-07'
        ];

        // return $colInv->toArray();

    }

    public function testBuildSalesReport1(){
        $ie = new InvoiceEntity;
        $ee = new EmployeeEntity;
        $pe = new ProductEntity;
        $arrPar = [
            'start_date'=>'2018-04-01',
            'end_date'=>'2018-04-07'
        ];
        $colInv = $ie->getColEloqWhere([
            ['created_at','>=',$arrPar['start_date']],
            ['created_at','<=',$arrPar['end_date']]
        ],1);
        // $colInv->transform(fun)
        $colEmp = $ee->getColEloqWhereIn('id',$colInv->pluck('employee_id'));
        $colInv->transform(function($obj)use($colEmp){
            $obj->employee_name =  $colEmp->where('id','=',$obj->employee_id)->first()->name;
            return $obj;
        });

        $colProductId = $colInv->pluck('InvoiceDetail.*.product_id')->flatten()->unique();
        $colProduct = $pe->getColEloqWhereIn('id',$colProductId);
        foreach($colInv as $inv){
            foreach($inv->invoiceDetail as $detail){
                $detail->product_name = $colProduct->where('id','=',$detail->product_id)->first()->name;
            }
        }
        return $colInv->toArray();

    }
    public function testStorePayment(){
        $arrData = [
            (object)[
                'invoice_id'=>1,
                'payment_type'=>'cash',
                'card_number'=>'11104238',
                'amount'=>100000,
            ],
            (object)[
                'invoice_id'=>1,
                'payment_type'=>'cash',
                'card_number'=>'22202343',
                'amount'=>200000,
            ],
            (object)[
                'invoice_id'=>1,
                'payment_type'=>'cash',
                'card_number'=>'333004234',
                'amount'=>300000,
            ],
        ];
        $res = $this->paymentEntity->createPayment($arrData);

    }

    public function storeInvoice(){
        $objData = (object)[
            'employee_id'=>3,
            'customer_id'=>3,
            'branch_id'=>3,
            'card_charge'=>120000,
            'invoiceDetail'=>[
                (object)['product_id'=>7],
                (object)['product_id'=>8],
                (object)['product_id'=>9]
            ]
        ];
        $res = $this->invoiceEntity->createInvoice($objData);
    }


}
