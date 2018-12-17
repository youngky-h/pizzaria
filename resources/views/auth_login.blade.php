<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>{{ env('APP_NAME') }} Store Management </title>
    
    <!-- Bootstrap -->
    <link href="{{ asset("css/bootstrap.min.css") }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset("css/font-awesome.min.css") }}" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="{{ asset("css/gentelella.min.css") }}" rel="stylesheet">
    <style type="text/css">
    	@font-face {
          font-family: goldoni;
          src: url('fonts/Goldoni.ttf.woff');
        }
    </style>
</head>

<body class="login">
<div>
    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
				<!-- <form role='form' method='post' action='manage_user/check_in'> -->
			
			    <h1><span style="font-family: goldoni; color: #4c5066;">{{ env('APP_NAME') }}</span> <br><small>Store Management</small></h1>
			    <div class='form-group'>
			        <input type="text" class="form-control" placeholder="User Name" name='login_acc_name' id='login_acc_name' required />
			    </div>
			    <div class='form-group'>
			        <input type="password" class="form-control" placeholder="Password" name='login_password' id='login_password' required />
			    </div>
			    <div class='form-group'>
			        <button type='submit' class="btn btn-default submit" onclick='login()'>Log in</button>
			    </div>
				<!-- </form> -->
 
			    <div class="separator"><div>
				@include('library_blade.row.msg-1')
			    <div class='form-group' style="margin-top: 10%;">
			    	<img style='width:70%;' src='{{asset("image/logo.png")}}'/>
				    <p>©2018 All Rights Reserved. UNICODE</p>
				</div>
		    </section>
        </div>
    </div>
</div>
<!-- jQuery -->
        <script src="{{ asset("js/jquery.min.js") }}"></script>
        <!-- Bootstrap -->
        <script src="{{ asset("js/bootstrap.min.js") }}"></script>
        <!-- Custom Theme Scripts -->
        <script src="{{ asset("js/gentelella.min.js") }}"></script>
@include('library_blade.x-js_base-2')
<script type = 'text/javascript'>
// $(document).ready(function(){
// 	$('#btn_submit').click(login)
// });
function login(){
	var loginAccName = $('#login_acc_name').val();
	var loginPassword = $('#login_password').val();

	// getAppPage(appUrl+'dashboard');
	objData ={
		'username':loginAccName,
		'password':loginPassword
	};

	x=apiController.sendRequest('login',objData).then(
		(obj,stStatus,xhr)=>{
			console.log({'msg':'success','obj':obj,'stStatus':stStatus,'xhr':xhr});
			displayMessage(obj,1);
			if (obj.status_code == 101){
				console.log({'token':obj.token});
				apiController.storeToken(obj.token);
				apiController.getAppPage('dashboard');
			}
		},
		(obj,stStatus,xhr)=>{
			displayMessage(obj,1);
			
			// console.log({'msg':'fail','obj':obj,'stStatus':stStatus,'xhr':xhr});
			// objMsg = {status_code:-1,message:'Percobaan login melebihi batas. Harap Tunggu 5 menit.'};
			// displayMessage(objMsg);
		}
	);
}
</script>
</body>
</html>