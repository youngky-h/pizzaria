<?php

namespace App\Http\Controllers;
use Cookie;

use App\Entity\UserAccountEntity;
use App\Entity\ProductEntity;
use App\Modules\AuthMod;
use App\Modules\IntraFlowControlMod;
use DNS1D;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PageServingController extends UiBaseController
{

    public function __construct(){
        parent::__construct();
        $this->userAccountEntity = new userAccountEntity;
    }

    protected function prepSessionData(Request $request)
    {
        $this->ifc = IntraFlowControlMod::getInstance();        
        $this->sessionInfo = $request->get('sessionInfo');

    }    

    // public function guardPage(Request $request, string $permissionName)
    // {
    //     $canAccess = AuthMod::isCurrentUserCanAccess($permissionName);
    //     if(!$canAccess){
    //     // dd([$canAccess,'r343']);
    //         // AuthMod::logout();
    //         return redirect()->route('auth_no_access')
    //             ->with('statusCode', '-100')
    //             ->with('message', 'Anda tidak mempunyai akses terhadap halaman ini.');
    //     }
    // }

    public function dashboard(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        // dd(['dash'=>$sessionInfo]);
        // dd($request->get('guardian'));
        return view('dashboard')
            ->with('guardian',$request->get('guardian'))        
            ->with('sessionInfo',$sessionInfo);
            // ->with('logs',$test);
    }

    public function serveSessionSetting(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        // dd(['dash'=>$sessionInfo]);
        // dd($request->get('guardian'));
        return view('session_setting')
            ->with('guardian',$request->get('guardian'))        
            ->with('sessionInfo',$sessionInfo);
            // ->with('logs',$test);
    }
    
    public function widget(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('widget')
            ->with('guardian',$request->get('guardian'))        
            ->with('sessionInfo',$sessionInfo);
            // ->with('logs',$test);
    }    
//======
//master 

    public function branch(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        // $this->guardPage($request, 'brw.master.branch');
        
        return view('master_branch')
            ->with('guardian',$request->get('guardian'))        
            ->with('sessionInfo',$sessionInfo);
            // ->with('logs',$test);
    }

    
    public function rack(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('rack')
            ->with('guardian',$request->get('guardian'))        
            ->with('sessionInfo',$sessionInfo);
            // ->with('logs',$test);
    }    

    public function serveMasterCompany(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('master_company')
            ->with('guardian',$request->get('guardian'))        
            ->with('sessionInfo',$sessionInfo);
            // ->with('logs',$test);
    }    

    public function serveMasterCompanyProfile(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('master_company_profile')
            ->with('guardian',$request->get('guardian'))        
            ->with('sessionInfo',$sessionInfo);
            // ->with('logs',$test);
    }    

//===========
//auth mod
    public function serveAuthEmployee(Request $request){
        // $this->prepSessionData($request);
        $sessionInfo=$request->get('sessionInfo');
        // dd(['pp0sdv', $sessionInfo]);
        return view('auth_employee')
            ->with('jsonGuardian', json_encode($request->get('guardian')))        
            ->with('guardian',$request->get('guardian'))        
            ->with('sessionInfo',$sessionInfo);
            // ->with('logs',$test);
    }
        
    public function changePassword(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('auth_change_password')
            ->with('guardian',$request->get('guardian'))        
            ->with('sessionInfo',$sessionInfo);
            // ->with('logs',$test);
    }    

    public function serveAuthUserAccount(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('auth_user_account')
            ->with('jsonGuardian', json_encode($request->get('guardian')))                
            ->with('guardian',$request->get('guardian'))        
            ->with('sessionInfo',$sessionInfo);
            // ->with('logs',$test);
    }  

    public function role(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('role')
            ->with('guardian',$request->get('guardian'))        
            ->with('sessionInfo',$sessionInfo);
            // ->with('logs',$test);
    }

//==============
//product modules
  
    public function manageCategory(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('category')
            ->with('guardian',$request->get('guardian'))        
            ->with('sessionInfo',$sessionInfo);
            // ->with('logs',$test);
    }    

    public function manageClass(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('product_class')
            ->with('guardian',$request->get('guardian'))        
            ->with('sessionInfo',$sessionInfo);
            // ->with('logs',$test);
    }

    public function manageProduct(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('manage_product')
            ->with('guardian',$request->get('guardian'))        
            ->with('sessionInfo',$sessionInfo);
            // ->with('logs',$test);
    }

    public function managePriceCategory(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('price_category')
            ->with('guardian',$request->get('guardian'))        
            ->with('sessionInfo',$sessionInfo);
            // ->with('logs',$test);
    } 

    public function manageSubCategory(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('sub_category')
            ->with('guardian',$request->get('guardian'))        
            ->with('sessionInfo',$sessionInfo);
            // ->with('logs',$test);
    }

//===================
//sales module

    public function dailySalesReportForm(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('sales_daily_sales_report_form')
            ->with('guardian',$request->get('guardian'))        
            ->with('sessionInfo',$sessionInfo);
    }

    public function dailySalesReportView(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        $date = $request->get('date');
        $branchId = (int)$request->get('branch_id');
        $categoryId = (int)$request->get('category_id');
        $reportParam = [
            'date'=>$date,
            'branch_id'=>$branchId,
            'category_id'=>$categoryId
        ];
        $strReportParam = json_encode($reportParam);
        // dd(['svav', $strReportParam]);
        return view('sales_daily_sales_report_view')
            ->with('strReportParam',$strReportParam)
            ->with('guardian',$request->get('guardian'))
            ->with('sessionInfo',$sessionInfo);
            // ->with('logs',$test);
    } 

    public function devisiSalesReportForm(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('sales_devisi_sales_report_form')
            ->with('guardian',$request->get('guardian'))        
            ->with('sessionInfo',$sessionInfo);

    }

    public function devisiSalesReportView(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $branchId = $request->get('branch_id');
        $reportParam = [
            'start_date'=>$startDate,
            'end_date'=>$endDate,
            'branch_id'=>$branchId,
        ];
        $strReportParam = json_encode($reportParam);
        return view('sales_devisi_sales_report_view')
            ->with('strReportParam',$strReportParam)
            ->with('guardian',$request->get('guardian'))
            ->with('sessionInfo',$sessionInfo);
            // ->with('logs',$test);
    } 

    public function manageSales(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        return view('sales_manage_sales')
            ->with('guardian', $request->get('guardian'))
            ->with('jsonGuardian', json_encode($request->get('guardian')))
            ->with('sessionInfo',$sessionInfo);

    }

    public function spgSalesReportForm(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('sales_spg_sales_report_form')
            ->with('guardian',$request->get('guardian'))        
            ->with('sessionInfo',$sessionInfo);

    }

    public function spgSalesReportView(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $branchId = $request->get('branch_id');
        $reportParam = [
            'start_date'=>$startDate,
            'end_date'=>$endDate,
            'branch_id'=>$branchId,
        ];
        $strReportParam = json_encode($reportParam);
        return view('sales_spg_sales_report_view')
            ->with('strReportParam',$strReportParam)
            ->with('guardian',$request->get('guardian'))
            ->with('sessionInfo',$sessionInfo);
            // ->with('logs',$test);
    } 

//================
//stock module
    
    public function stockChangeBarcode(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('change_barcode')
            ->with('guardian',$request->get('guardian'))        
            ->with('sessionInfo',$sessionInfo);
            // ->with('logs',$test);
    }    


//------------mutation submod
    public function serveStockMutationReportView(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        $date = $request->get('date');
        $branchId = $request->get('branch_id');
        $type = $request->get('type');
        $reportParam = [
            'mutation_date'=>$date,
            'branch_id'=>$branchId,
            'type'=>$type
        ];
        $strReportParam = json_encode($reportParam);
        if($type == 'new-product'){
            $strTitle = 'Laporan Produk Baru';
            $strBranch = 'Lokasi';
        }elseif($type == 'sold'){
            $strTitle = 'Laporan Produk Terjual';
            $strBranch = 'Cabang Asal';
        }elseif($type == 'to-delete'){
            $strTitle = 'Laporan Produk Dihapus';
            $strBranch = 'Cabang Asal';
        }elseif($type == 'to-scrap'){
            $strTitle = 'Laporan Produk Dilebur';
            $strBranch = 'Cabang Asal';
        }elseif($type == 'to-store'){
            $strTitle = 'Laporan Penambahan Stok Toko';
            $strBranch = 'Cabang Tujuan';
        }else{
            $strTitle = 'Laporan Pengembalian Stok Toko';
            $strBranch = 'Cabang Asal';
        }
        return view('stock_mutation_report_view')
            ->with('strReportParam',$strReportParam)
            ->with('guardian',$request->get('guardian'))        
            ->with('sessionInfo',$sessionInfo)
            ->with('strTitle',$strTitle)
            ->with('strBranch',$strBranch);
            // ->with('logs',$test);
    } 

    public function stockPrintLabel(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('print_label')
            ->with('guardian',$request->get('guardian'))        
            ->with('sessionInfo',$sessionInfo);
            // ->with('logs',$test);
    }
    
    public function stockMutationReportNewProduct(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('mutation_report_form')
            ->with('guardian',$request->get('guardian'))        
            ->with('pageTitle', 'Laporan Produk Baru')
            ->with('strBranch', 'Cabang Asal')
            ->with('mutationType', 'new-product')            
            ->with('sessionInfo',$sessionInfo);
    }
    
    public function stockMutationReportScrappedProduct(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('mutation_report_form')
            ->with('guardian',$request->get('guardian'))        
            ->with('pageTitle', 'Laporan Lebur Produk')
            ->with('strBranch', '-')
            ->with('mutationType', 'to-scrap')            
            ->with('sessionInfo',$sessionInfo);
    }
    
    public function stockMutationReportStoreToWarehouse(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('mutation_report_form')
            ->with('guardian',$request->get('guardian'))        
            ->with('pageTitle', 'Laporan Pengembalian Stok Toko')
            ->with('strBranch', 'Cabang Asal')
            ->with('mutationType', 'to-warehouse')
            ->with('sessionInfo',$sessionInfo);
    }

    public function stockMutationReportSoldProduct(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('mutation_report_form')
            ->with('guardian',$request->get('guardian'))        
            ->with('pageTitle', 'Laporan Produk Terjual')
            ->with('strBranch', 'Cabang Asal')
            ->with('mutationType', 'sold')            
            ->with('sessionInfo',$sessionInfo);
    }

    public function stockMutationReportWarehouseToStore(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('mutation_report_form')
            ->with('guardian',$request->get('guardian'))        
            ->with('pageTitle','Laporan Penambahan Stok Toko')
            ->with('strBranch', 'Cabang Tujuan') 
            ->with('mutationType','to-store')           
            ->with('sessionInfo',$sessionInfo);
    }    

    public function stockMutationReplenishBranch(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('mutation_replenish_branch')
            ->with('guardian',$request->get('guardian'))        
            ->with('sessionInfo',$sessionInfo);

    }

    public function stockMutationReturnWarehouse(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('mutation_return_warehouse')
            ->with('guardian',$request->get('guardian'))        
            ->with('sessionInfo',$sessionInfo);
    }

    public function stockMutationScrap(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('mutation_scrap')
            ->with('guardian',$request->get('guardian'))
            ->with('sessionInfo',$sessionInfo);
    }

//----------------stock opname submod
    public function stockOpnameManage(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('stock_opname_manage')
            ->with('guardian',$request->get('guardian'))        
            ->with('sessionInfo',$sessionInfo);
    }

    public function stockOpnameReportForm(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('stock_opname_report_form')
            ->with('guardian',$request->get('guardian'))  
            ->with('jsonGuardian', json_encode($request->get('guardian')))
            ->with('pageTitle', 'Laporan Stock')
            ->with('strBranch', 'Lokasi')
            ->with('sessionInfo',$sessionInfo);
    }

    public function stockOpnameAuditorReportView(Request $request){
        $sessionInfo=$request->get('sessionInfo');

        $reportParam = [
            'soa_id'=>(integer)$request->get('soa_id')
        ];
        $strReportParam = json_encode($reportParam);        
        return view('stock_opname_auditor_report_view')
            ->with('guardian',$request->get('guardian'))
            ->with('strReportParam',$strReportParam)            
            ->with('reportTitle', 'Laporan Stock')
            ->with('strBranch', 'Lokasi')
            ->with('sessionInfo',$sessionInfo);
    }

    public function stockOpnameOwnerReportView(Request $request){
        $sessionInfo=$request->get('sessionInfo');

        $reportParam = [
            'soa_id'=>(integer)$request->get('soa_id')
        ];
        $strReportParam = json_encode($reportParam);        
        return view('stock_opname_owner_report_view')
            ->with('guardian',$request->get('guardian'))
            ->with('strReportParam',$strReportParam)            
            ->with('reportTitle', 'Laporan Stock')
            ->with('strBranch', 'Lokasi')
            ->with('sessionInfo',$sessionInfo);
    }

//-------------manifest submod
    public function stockManifestReportForm(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        
        return view('stock_manifest_report_form')
            ->with('guardian',$request->get('guardian'))        
            ->with('strBranch', 'Lokasi')
            ->with('sessionInfo',$sessionInfo);
    }

    public function stockManifestReportView(Request $request){
        $sessionInfo=$request->get('sessionInfo');
        $smId = $request->get('sm_report_id');
        $reportParam = [
            'sm_report_id'=>(integer)$smId,
        ];
        $strReportParam = json_encode($reportParam);
        return view('stock_manifest_report_view')
            ->with('strReportParam',$strReportParam)
            ->with('guardian',$request->get('guardian'))
            ->with('reportTitle', 'Laporan Manifes Stok')
            ->with('sessionInfo',$sessionInfo);
            // ->with('logs',$test);
    }

}
