<script type='text/javascript'>
const apiController = {
	appUrl: 'http://dev.macan.local/',
	token: '',
	build : function(username,password){
		return {
			username: username,
			password: password,
			platform:'web',
			version: '1',
		}
	},
	getAppPage : function (action){
		window.location.href=this.appUrl+action+'?token='+this.getToken();
	}
	getAppUrl:function(){
		return this.appUrl;
	},
	getToken:function(){
		return this.token
	},
	sendRequest: function (action,objData){
		objParam = {
			platform:'web',
			version:'1'
		};
		if(this.getToken()){
			objParam.token=this.getToken;
		}
		for (x in objData){
		 	objParam[x]=objData[x];   
		}
		return Promise.resolve($.post(this.appUrl+'api/funct/'+action, objParam));		
	},
	storeToken:function(token){
		setCookie('token',token,6);
		this.token = token
	},
}
</script>