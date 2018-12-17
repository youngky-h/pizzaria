<!-- top navigation -->
<div class="top_nav">
    <div class="nav_menu">
        <nav>
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img src="/storage/employee_image/1.jpg" alt="Avatar of {{ $sessionInfo['userName'] }}">
                        <div id='topbar-text'></div>
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li><a id='linkAuthChangePassword'>Ubah Sandi</a></li>
                        @if($guardian['brw.master.company_profile'])
                        <li><a id='linkMasterCompanyProfile'>Profil Perusahaan</a></li>
                        @endif
                        @if($guardian['brw.session_setting'])
                        <li><a id='linkSessionSetting'>Pengaturan Sesi</a></li>
                        @endif                        
                        <li><a id='linkAuthLogout'><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                    </ul>
                </li>

            </ul>
        </nav>
    </div>
</div>
<!-- /top navigation -->
@push('scripts')
<script type='text/javascript'>
$('#linkAuthLogout').on("click", function(event){
        event.preventDefault();
        eraseCookie('token');
        window.location.href='/login';
});
$('#linkAuthChangePassword').on("click", function(event){
        event.preventDefault();
        window.location.href='/auth_change_password';
});

$('#linkMasterCompanyProfile').on("click", function(event){
        event.preventDefault();
        window.location.href='/master_company_profile';
});
$('#linkSessionSetting').on("click", function(event){
        event.preventDefault();
        window.location.href='/session_setting';
});
</script>
@endpush