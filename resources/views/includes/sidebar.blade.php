
<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <!-- <a href="{{-- url('/') --}}" class="site_title"><img style='width:50px;' src='{{--asset("storage/icon_macan.png")--}}'/><span> Macan Gold</span></a> -->
            <a href="{{ url('/') }}" class="site_title"><img style='width:50px;' src='{{asset("image/icon.png")}}'/><span> {{ env('APP_NAME') }}</span></a>
        </div>
        
        <div class="clearfix"></div>
        
        <!-- menu profile quick info -->
        <div class="profile">
            <div class="profile_pic">
                <img src='/storage/employee_image/1.jpg' alt="Avatar of {{ $sessionInfo['userName'] }}" class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2>{{ $sessionInfo['userName'] }}</h2>
            </div>
        </div>
        <!-- /menu profile quick info -->
        
        <div class="clearfix"></div>
        
        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <ul class="nav side-menu">
                    <li>
                        <a id='linkDashboard' href='dashboard'>
                            <i class="fa fa-home"></i>
                            Dashboard
                        </a>
                    </li>
                    @if($guardian['brw.auth.menu_group']) 
                    <li><a><i class="fa fa-users"></i> Pengguna <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li>
                                <a id='linkAuthUserAccount' href='auth_user_account'>
                                    <i class="fa fa-user"></i>
                                    Kelola Pengguna
                                </a>
                            </li>
                            <li>
                                <a id='linkAuthEmployee' href='auth_employee'>
                                    <i class="fa fa-user"></i>
                                    Kelola Pegawai
                                </a>
                            </li>
                            @if($guardian['brw.auth.role'])
                            <li>
                                <a id='linkAuthRole' href='auth_role'>
                                    <i class="fa fa-user"></i>
                                    Kelola Jabatan
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if($guardian['brw.master.company'])
                    <li>
                        <a id='linkMasterCompany' href='master_company'><i class="fa fa-cube"></i> Kelola Perusahaan <!-- <span class="fa fa-chevron-down"></span> --></a>
                    </li>
                    @endif                    
                    @if($guardian['brw.product.menu_group']) 
                    <li><a><i class="fa fa-cubes"></i> Produk <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @if($guardian['brw.product.manage_product'])
                            <li><a id='linkProductManageProduct' href='product_manage_product'><i class="fa fa-cube"></i>Kelola Produk</a></li>
                            @endif
                            @if($guardian['brw.master.category'])
                            <li><a id='linkProductManageCategory' href='product_manage_category'><i class="fa fa-laptop"></i>Kelola Kategori</a></li>
                            @endif
                            @if($guardian['brw.master.sub_category'])
                            <li><a id='linkProductManageSubCategory' href='product_manage_sub_category'><i class="fa fa-laptop"></i>Kelola Sub Kategori</a></li>
                            @endif
                            @if($guardian['brw.master.price_category'])
                            <li><a id='linkProductManagePriceCategory' href='product_manage_price_category'><i class="fa fa-laptop"></i>Kelola Kategori Harga</a></li>
                            @endif
                            @if($guardian['brw.master.class'])
                            <li><a id='linkProductManageClass' href='product_manage_class'><i class="fa fa-laptop"></i>Kelola Kelas Produk</a></li>
                            @endif
                            @if($guardian['brw.stock.print_label'])
                            <li><a id='linkStocktPrintLabel' href='stock_print_label'><i class="fa fa-print"></i>Cetak Label</a></li>
                            @endif
                            @if($guardian['brw.stock.change_barcode'])
                            <li><a id='linkStockChangeBarcode' href='stock_change_barcode'><i class="fa fa-refresh"></i>Ganti Barcode</a></li>
                            @endif                                 
                        </ul>
                    </li>
                    @endif
                    @if($guardian['brw.sales.manage_sales'])
                    <li>
                        <a id='linkSalesManageSales' href='sales_manage_sales'><i class="fa fa-cube"></i> Kelola Penjualan <!-- <span class="fa fa-chevron-down"></span> --></a>
                    </li>
                    @endif
                    @if($guardian['brw.stock.mutation.menu_group'])
                    <li><a><i class="fa fa-refresh"></i> Mutasi Stok <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a id='linkStockMutationReplenishBranch' href='stock_mutation_replenish_branch'>Penambahan Stok Cabang</a></li>
                            <li>
                            <li><a id='linkStockMutationReturnWarehouse' href='stock_mutation_return_warehouse'>Pengembalian Stok Cabang</a></li>
                            <li>                                
                            <li><a id='linkStockMutationScrap' href='stock_mutation_scrap'>Lebur</a></li>
                        </ul>
                    </li>
                    @endif
                    @if($guardian['brw.stock.opname.manage'])
                    <li>
                        <a id='linkStockOpnameManage' href='stock_opname_manage'><i class="fa fa-cube"></i> Kelola Stok Opnam</a>
                    </li>
                    @endif                    
                    @if($guardian['brw.report_menu_group'])
                    <li><a><i class="fa fa-book"></i> Laporan <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                        @if($guardian['brw.sales.daily_sales_report_form'])
                            <li><a id='linkSalesDailySalesReportForm' href='sales_daily_sales_report_form'>Laporan Penjualan Harian</a></li>
                        @endif

                        @if($guardian['brw.sales.devisi_sales_report_form'])
                            <li><a id='linkSalesDevisiSalesReportForm' href='sales_devisi_sales_report_form'>Laporan Penjualan Per Devisi</a></li>
                        @endif

                        @if($guardian['brw.sales.spg_sales_report_form'])
                            <li><a id='linkSalesSpgSalesReportForm' href='sales_spg_sales_report_form'>Laporan Penjualan Per SPG</a></li>
                        @endif          

                        @if($guardian['brw.stock.mutation.report_warehouse_to_store'])
                            <li><a id='linkStockMutationReportWarehouseToStore' href='stock_mutation_report_warehouse_to_store'>Laporan Mutasi Penambahan Stok Toko</a></li>
                        @endif

                        @if($guardian['brw.stock.mutation.report_store_to_warehouse'])
                            <li><a id='linkStockMutationReportStoreToWarehouse' href='stock_mutation_report_store_to_warehouse'>Laporan Mutasi Pengembalian Stok Toko</a></li>
                        @endif

                        @if($guardian['brw.stock.mutation.report_scrapped_product'])
                            <li><a id='linkStockMutationReportScrappedProduct' href='stock_mutation_report_scrapped_product'>Laporan Mutasi Lebur</a></li>                       
                        @endif

                        @if($guardian['brw.stock.mutation.report_new_product'])
                            <li><a id='linkStockMutationReportNewProduct' href='stock_mutation_report_new_product'>Laporan Mutasi Produk Baru</a></li>
                        @endif

                        @if($guardian['brw.stock.mutation.report_sold_product'])
                            <li><a id='linkStockMutationReportSoldProduct' href='stock_mutation_report_sold_product'>Laporan Mutasi Produk Terjual</a></li>
                        @endif
                        
                        @if($guardian['brw.stock.manifest.report_form'])
                            <li><a id='linkStockManifestReportForm' href='stock_manifest_report_form'>Laporan Manifes Stok</a></li>   
                        @endif
                        @if($guardian['brw.stock.opname.report_form'])
                            <li><a id='linkStockOpnameReportForm' href='stock_opname_report_form'>Laporan Stok Opname</a></li>   
                        @endif                        
                        </ul>
                    </li>
                    @endif
                    @if($guardian['brw.master.branch'])
                    <li>
                        <a id='linkBranch' href='branch'>
                            <i class="fa fa-laptop"></i>
                            Kelola Cabang
                        </a>
                    </li>
                    @endif
                    @if($guardian['brw.master.rack'])
                    <li>
                        <a id='linkRack' href='rack'>
                            <i class="fa fa-laptop"></i>
                            Kelola Rak
                        </a>
                    </li>
                    @endif
                    @if(env('APP_DEBUG',false)  && $guardian['brw.developer'])
                    <li>
                        <a id='linkDev' href='developer'>
                            <i class="fa fa-laptop"></i>
                            Exp
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        
        </div>
        <!-- /sidebar menu -->
        
    </div>
</div>
@push('scripts')
<script type='text/javascript'>

$('#linkDashboard').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('dashboard');
});

$('#linkDev').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('developer');
});

//etc master

$('#linkBranch').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('branch');
});

$('#linkMasterCompany').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('master_company');
});

$('#linkRack').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('rack');
});

//auth module

$('#linkAuthRole').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('auth_role');
});

$('#linkAuthUserAccount').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('auth_user_account');
});

$('#linkAuthEmployee').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('auth_employee');
});

$('#linkAuthWidget').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('auth_widget');
});

//product module
$('#linkProductManageCategory').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('product_manage_category');
});

$('#linkProductClass').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('product_manage_class');
});

$('#linkProductManageProduct').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('product_manage_product');
});

$('#linkProductManagePriceCategory').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('product_manage_price_category');
});

$('#linkProductManageSubCategory').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('product_manage_sub_category');
});

//sales module
$('#linkSalesManageSales').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('sales_manage_sales');
});

$('#linkSalesDailySalesReportForm').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('sales_daily_sales_report_form');
});

$('#linkSalesDevisiSalesReportForm').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('sales_devisi_sales_report_form');
});

$('#linkSalesSpgSalesReportForm').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('sales_spg_sales_report_form');
});

//stock module

$('#linkStockChangeBarcode').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('stock_change_barcode');
});

$('#linkStockMutationReportWarehouseToStore').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('stock_mutation_report_warehouse_to_store');
});

$('#linkStockMutationReportStoreToWarehouse').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('stock_mutation_report_store_to_warehouse');
});

$('#linkStockMutationReportScrappedProduct').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('stock_mutation_report_scrapped_product');
});

$('#linkStockMutationReportNewProduct').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('stock_mutation_report_new_product');
});

$('#linkStockMutationReportSoldProduct').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('stock_mutation_report_sold_product');
});

$('#linkStockManifestReportForm').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('stock_manifest_report_form');
});

$('#linkStockMutationReplenishBranch').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('stock_mutation_replenish_branch');
});

$('#linkStockMutationReturnWarehouse').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('stock_mutation_return_warehouse');
});

$('#linkStockMutationScrap').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('stock_mutation_scrap');
});

$('#linkStockOpnameManage').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('stock_opname_manage');
});

$('#linkStockOpnameReportForm').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('stock_opname_report_form');
});

$('#linkStockPrintLabel').on("click", function(event){
        event.preventDefault();
        apiController.getAppPage('stock_print_label');
});

</script>
@endpush