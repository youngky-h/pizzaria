/*jshint esversion: 6 */
// "use strict";

const invoice = {
    fnCalculatePayment : function (payments){
        return payments.reduce((carry,payment)=>carry+parseFloat(payment.amount)-parseFloat(payment.change)+parseFloat(payment.card_charge),0);
    }
};

var app = {
    init : function (){
        this.guardian = getLocalJson('store_guardian')[0];
        this.sessionInfo = getLocalJson('session_info')[0];
        this.sessionSetting = {};
        if(this.sessionInfo.roleId == 1){
            jsnSessionSetting = getCookie('session_setting');
            obSessionSetting = JSON.parse(jsnSessionSetting);
            this.sessionSetting = obSessionSetting;
            console.log(['r32rf', this.sessionSetting, this.sessionInfo]);
        }
        console.log(getLocalJson('session_info')[0]);
        this.setTopbarText();

    },
    getGuardian : function () { return this.guardian;},
    getSessionInfo : function () { return this.sessionInfo;},
    getSessionSetting : function () {

    },
    isAuthorized : function(stAccessName){
        return stAccessName in this.guardian && this.guardian[stAccessName];
    },
    enableModal : function (stModalId) {
        $('#'+stModalId + '#btn_submit')
    },
    setTopbarText : function(){
        $('#topbar-text').html(this.sessionInfo.userName +
            ' - ' + this.sessionInfo.roleName + ' - ' + this.sessionInfo.companyName);
    }


};
const helper = {
    clone : function (thing){
        return JSON.parse(JSON.stringify(thing));
    },
    toConsole : function (thing){
        console.log(this.clone(thing));
    }
}
const numberWithCommas = (x) => {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
};


const simpleRound = function(value, decimals) {
  return Math.round(value*Math.pow(10,decimals))/Math.pow(10,decimals);
}



//================
//communication 

const apiController = {

    checkStatus : function(objResponse){
        stat = objResponse.status_code;
        isLoginNeeded = stat <= -100 && stat > -200;  
        if (isLoginNeeded){
            this.getAppPage('login');
        }
    },    
    afterModelAction : function(modalId, objResponse){
        stat = objResponse.status_code;
        this.checkStatus(objResponse);
        $('#'+modalId).modal('hide');
        $('#'+modalId + ' #btn_submit').prop('disabled', false);
        displayMessage(objResponse,1);
    },
    build : function(username,password){
        return {
            username: username,
            password: password,
            platform:'web',
            version: '1'
        };
    },    
    getAppPage : function(action){
        window.location.href=action+'?token='+this.getToken();
    },
    getAppUrl:function(){
        return this.appUrl;
    },

    getToken:function(){
        return getCookie(this.tokenName);
    },
    setTokenName:function(tokenName){
        this.tokenName = tokenName;
    },
    sendRequest: function(action,objData){
        objParam = {
            platform:'web',
            version:'1',
            data:{}
        };
        objParam[this.tokenName]=this.getToken();
        objParam.data = JSON.stringify(objData);      
        return Promise.resolve($.post('api/funct/'+action, objParam));      
    },
    storeToken:function(token){
        setCookie(this.tokenName,token,6);
    },
    token: '',
    tokenName: 'token'

};



const fetchData = function( strUrl, objParam){
    console.log('fetching...');
    if (objParam==null){
        objParam = {};
    }
    objParam['_token']=$("#token").val();
    return Promise.resolve($.post(strUrl, objParam));
};

//==================
//DATA MANIPULATION//

/**
 * convert array to object which element is from array element
 * @param  {[type]} objData [description]
 * @return {[type]}         [description]
 */
const arrToObject = function(arrData){
    return arrData.reduce(
        function(acc, cur, i) {
            acc[i] = cur;
            return acc;
        }, {}
    );
};

/**
 * [convertDecimalSeparator description]
 * @param  {string} strNumber number with decimal separator
 * @return {string}           number with US decimal separator
 * input ex: 2344,35
 * outptu ex: 2344.35
 */
const convertDecimalSeparator= function(strNumber){
    return strNumber.replace(',','.');

};

const convertDecimalSeparatorTo= function(strNumber,strFrom,strTo){
    // console.log(strNumber);
    return strNumber.replace(strFrom, strTo);

};

const convertToBase64 = function(objFile){
    x= new Promise(function(resolve, reject) {
        let fr = new FileReader();
        fr.onloadend = () => resolve(fr.result);
        fr.onerror = reject;
        fr.readAsDataURL(objFile);
    });
    return x;
};

/**
 *Get object having id in array of object
 *@param {array object} arrObject 
 *@param {int} id
 *@return { object } obj,  
 */
//
const findInArrObject = function(arrObject,id){
    obj = null;
    for (let obj of arrObject){
        if (obj.id == id){
            return obj;
        }
    }

    return obj;
};

/**
 * input:
 *  array of object
 *  object of criteria, example
 *        {name:'john', age:20}
 * output: array of object satisfy criteria        
 */
const getArrFilteredObject = function(arrData, objCriteria){
    if (!Array.isArray(arrData)){
        console.log('Err in filterArrObject: 1st parameter must be array');
        return [];
    }
    if (typeof objCriteria != 'object'){
        console.log('Err in filterArrObject: 2nd parameter must be an object');
        return [];
    }    
    return $.grep(arrData, function(v) {
        for (let prop in objCriteria){
            if (v[prop] != objCriteria[prop]){
                return false;
            }
        }
        return true;
    });
};

/*
input: - 
output: string current date in format of yyyy-mm-dd, ex: 2017-02-24
 */
const getStrTodayDate = function(){
    dtCurrent = new Date(Date.now());
    currentYear = dtCurrent.getFullYear();
    currentMonth = dtCurrent.getMonth()+1;
    currentDay = dtCurrent.getDate();
    if (currentMonth<10){
        currentMonth = '0'+currentMonth;
    }
    if (currentDay<10){
        currentDay = '0'+currentDay;
    }
    
    return currentYear+'-'+currentMonth+'-'+currentDay;
};

/**
 * convert object to array which element is from object property
 * @param  {[type]} objData [description]
 * @return {[type]}         [description]
 */
const objToArray = function(objData){
     return Object.keys(objData).map(
        function(key) { return objData[key] })
};

const removeElement = function(arrData, id){
        x = findInArrObject(arrData, id);
        pos = arrData.indexOf(x);
        arrData.splice(pos, 1);
};

//data table function
//===================
//

const appendDataTableRow = function(strElmId, rowData){
    $('#'+strElmId).DataTable().rows.add([rowData]).draw();
};

const fillDataTable = function(objParam){
    opStatus = -1;
    if(typeof objParam == "object"){
        opStatus = 1;
    }

    let elmTargetTbl = $('#'+objParam.tableId+' >tbody');
    elmTargetTbl.html('');
    if(typeof objParam.fnAjax=="undefined"){
        fnAjax = function(d){
            return {
                token:apiController.getToken(),
                platform:'web',
                version:"1",
                data: JSON.stringify(d)
            };
        };
    }
    $('#'+objParam.tableId).DataTable({
        processing:true,
        responsive:true,
        paging: true,
        dom: 'rftip',
        // pagingType: "numbers",
        serverSide:true,
        // ajax:function(data, callback, settings){
        //     apiController.sendRequest('experiment/fetch_active_entity',data).then(callback);
        // },
        ajax:{
            url: "api/funct/"+objParam.fetchUrl,
            type: "POST",
            data: fnAjax,
            dataSrc: objParam.fnDataTransformer,
        },
        columns: objParam.column
    });          
};
//get all data table rows
const getDataTableAll = function(strElmId){
    arrData = [];
    table = $('#'+strElmId).DataTable();
    table.rows().every(function(rowIdx) {
          arrData.push(table.row(rowIdx).data())
       });
    return arrData;
};

// public function getDataTableRowForIdx
//depend on current sort
const getDataTableRowForId = function(strElmId, id){
    tableData = $('#'+strElmId).DataTable().rows({order:'index'}).data();
    for(let i = 0; i< tableData.count();i++){
        if(tableData[i].id==id){
            return {idx:i, data:tableData[i]};
        }
    }
    return null;
    // return tableData[idx];
};

const getDataTableRowMatched = function(strElmId, id){
    tableData = $('#'+strElmId).DataTable().rows().data();
    myArray = [];
    for(let i = 0; i< tableData.count();i++){
        if(tableData[i].id==id){
            myArray.push({idx:i, data:tableData[i]});
        }
    }
    return myArray;
    // return tableData[idx];
};

const redrawDataTable = function(strTableId){
    $('#'+strTableId).DataTable().draw('full-hold');
};

const removeDataTableRow = function(strElmId, rowIdx){
    $('#'+strElmId).DataTable().row(rowIdx).remove().draw();
};

//depend on current sort and filter
const updateDataTableRow = function(strElmId, rowIdx, rowData){
    $('#'+strElmId).DataTable().row(rowIdx).data(rowData).draw('page');

};


//========================
//data storage related function
//==============


const appendLocalJson = function(data, strElmId){
    res = [];
    merged=res.concat(getLocalJson(strElmId));
    merged.push(data);
    setLocalJson(merged, strElmId);
};

const eraseCookie = function(name) {   
    document.cookie = name+'=; Max-Age=-99999999;';  
};

const getCookie = function(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
};

/**
 * abstraction to get data from element
 * accepted 1st parameter:
 * elm tag: div, input
 * type: string
 * output: arr
 */
const getLocalJson = function(strElmId){
    res = [];
    if(typeof strElmId != 'string'){
        console.log('getLocalJSon: 1st parameter must be string');
        return res;
    }    
    targetElm = $('#'+strElmId);
    tagName = targetElm.prop('tagName');
    //check if id exist
    if (targetElm.length == 0) {
        console.log('getLocalData: id not exist '+strElmId);
        return res;
    }
    data = '';
    //for div get html content
    if ( tagName == 'DIV'){
        console.log('getLocalJson: source is div.');
        data = targetElm.text();
        // console.log(data);
    }
    //for tag input
    if ( tagName == 'INPUT'){
        console.log('getLocalJson: source is input.');
        data =  targetElm.val();
    }

    if (data==''){
        console.log('getLocalJson: empty element.');
        return [];
    }

    //try to parse
    try{
        res = JSON.parse(data);
        // console.log(['getLocalData: parsing json successfully.',data]);
    }
    catch (e){
        console.log('getLocalData: err parse json.');
    }
    if(!Array.isArray(res)){
        res = [res];
    }
    return res;
};



const setCookie= function(name,value,hours) {
    var expires = "";
    if (hours) {
        var date = new Date();
        date.setTime(date.getTime() + (hours*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
};

//rename setLocalJson to this
const storeDataLocally = function(data, storageId){
};

//abstraction to set var data to element
//accepted input 1 parameter:
//  elm tag: div, input
//  type: string
//  output: boolean
const setLocalJson = function(data, strElmId){
    if(typeof strElmId != 'string'){
        console.log('setLocalData: 2nd parameter must be string');
        return false;
    }
    try{
        strData = JSON.stringify(data);
    }
    catch(e){
        console.log('setLocalData: err stringify json');
        return false;
    }
    targetElm = $('#'+strElmId);
    tagName = targetElm.prop('tagName');
    //check if id exist
    if (targetElm.length == 0) {
        console.log('setLocalData: element not found');
        return false;
    }    

    if ( tagName == 'DIV'){
        targetElm.text(strData);
    //for tag input
    } else if ( tagName == 'INPUT'){
        targetElm.val(strData);
    } else {
        return false;
    }

    return true;

};

//===============================
//html manipulation related function
//=====================
const attachResetter = function(strMdlId){
    $('#'+strMdlId).on('hide.bs.modal', function(){
        $('#frm_'+strMdlId).data('validator').resetForm();
        $('#frm_'+strMdlId)[0].reset();
    });
};

const clearForm = function(arrSelector){
    if (Array.isArray(arrSelector)){
        for (id of arrSelector){
            $(id).val('');
        }
    }

};

/**
 * construct row of table
 * @param  {array} arrCellData 
 * @return {string} string contain html tag of row
 */
const constructRow = function(arrCellData){
    strRes = '<tr>';
    for(let cellData of arrCellData){
        strRes += '<td>' + cellData + '</td>';
    }
    strRes += '</tr>';
    return strRes;
};

//require to include us_comp.row.msg-1
const displayMessage = function(objData, withLog = 0){
    if (objData.message == null){
        return null;
    }
    let displayedMsg = '<p>'+objData.message+'</p>';
    let strLogs = '';


    if(withLog &&  objData.logs!=null){
        strLogs = '<div>'+objData.logs+'</div>';
        // for (logItem of arrLogs){
        //     strLogs += JSON.stringify(logItem)+'<br>';
        // }            
        // strLogs += '</p>';
        $displayedMsg = displayedMsg + strLogs;
    }else{
        $displayedMsg  = displayedMsg;
    }
    if (parseInt(objData.status_code,10)<0) {
        $('#info_message').hide();            
        $('#err_message').html($displayedMsg);
        $('#err_message').show();
    } else {
        $('#err_message').hide();        
        $('#info_message').html($displayedMsg);
        $('#info_message').show();            
    }
};


const  initModalForm = function(strMdlId, funcSubmit, useValidator=true){
    $('#'+strMdlId).keyup(()=>{
        $('#'+strMdlId+' #btn_submit').prop('disabled', false);
    });
    $('#'+strMdlId+' #btn_cancel').click(()=>{
        $('#'+strMdlId+' #btn_submit').prop('disabled', false);
    });
    if (useValidator){
        attachResetter(strMdlId);
        $('#frm_'+ strMdlId).validate();
        $('#'+strMdlId+' #btn_submit').click(function(){
            $('#'+strMdlId+' #btn_submit').prop('disabled', true);
            res = $('#frm_'+strMdlId).valid();
            if (res){
                funcSubmit();
            }
        }); 
    } else {
        $('#'+strMdlId+' #btn_submit').click(function(){
            $('#'+strMdlId+' #btn_submit').prop('disabled', true);            
            funcSubmit();
        });        
    }
};

const initValidator = function(){
    $.validator.setDefaults({
        submitHandler: function() {
            alert("submitted!");
        },
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }        
    });
};
//objDataMap example { id: id, name: sku_name}

const setCbo = function(strTargetCbo, arobData, strValueSelected='', objDataMap={id:'id',name:'name'}){
    var elmTargetCbo = $('#'+strTargetCbo);
    elmTargetCbo.html('');
    for (var objData of arobData) {
        var strHTML = '<option value="'+objData[objDataMap.id]+'">'+objData[objDataMap.name]+'</option>';
        elmTargetCbo.append(strHTML);
    }
    if(strValueSelected !== ''){
        elmTargetCbo.val( strValueSelected)
    }
};

function setCboFromDiv(strSourceElm, strTargetCbo){
    var arrData = JSON.parse($('#'+strSourceElm).html());
    setCbo(strTargetCbo, arrData);
};

const setCboFromServer = function( strTargetCbo, strUrl, objParam,fnDataProcessor){
    return apiController.sendRequest(strUrl, objParam).
    then(function(objData) {
        if(typeof fnDataProcessor == 'function'){
            arobData = fnDataProcessor(objData.data);
        } else {
            arobData = objData.data;
        }
        setCbo(strTargetCbo, arobData);
    });
};

const resetCombo = function(strSelector){
    $(strSelector).val($(strSelector).find('option').first().val());
};




$(document).ready(function(){
  var intials = $('.profile_info h2').text().charAt(0);
  var profileImage = $('.profile .profile_pic').text(intials);
  $('.navbar-right > li > a > img').replaceWith('<div class="profile-pic" style="height: 100%;float: left;"></div>');
  $('.profile-pic').text(intials);

  // $('head').append(`<link href="../css/gdp.css" rel="stylesheet" type="text/css" />`);
});

// (function () {
    //input: number/ string represent non exponent number
const accurateRound = function (value, decimals) {
  return +(Math.round(value+'e'+decimals)+'e'+decimals*-1);
}

const formatDecimal = (numericValue, fractionDigit, space=0) => {
    roundedNumber = accurateRound(numericValue,fractionDigit);
    strNumber = roundedNumber.toString();
    formattedValue = convertDecimalSeparatorTo(strNumber,'.', ',')
        .replace(/\B(?=(\d{3})+(?!\d))/g, ".");        
    
    if(fractionDigit!== 0){
        pointPosition = strNumber.indexOf('.');
        if(pointPosition===-1){
            fractionPadding = ',';
            decimalCount = 0;
        } else {
            fractionPadding = '';
            decimalCount = strNumber.length-pointPosition-1;
        }
        fractionPadding += '0'.repeat(fractionDigit-decimalCount);
        formattedValue += fractionPadding;
    }
    
    padCount = space-formattedValue.length > 0 ? space-formattedValue.length:0;
    spacePad = '&nbsp;'.repeat(padCount); 
    return spacePad + formattedValue;
};

const formatLongRupiah = (numericValue) => {
    // console.log(['43ft344', numericValue]);
    return 'Rp. '+formatDecimal(numericValue,0, 14);
};

const formatCurrency = (curSymbol, numericValue, size) => {
    mpSize = new Map([['short',9],['med', 11],['long', 14]]);
    return curSymbol + formatDecimal(numericValue,0,mpSize.get(size));
}

const formatMedRupiah = (numericValue) => {
    // console.log(['43ft344', numericValue]);
    return 'Rp. '+formatDecimal(numericValue,0, 11);
};

const formatShortRupiah = (numericValue) => {
    return 'Rp. '+formatDecimal(numericValue,0, 9);
};

const formatGram = (numericValue, space = 6) => {
    return formatDecimal(numericValue,2,6);
}

const formatPcs = (numericValue) => {
    return formatDecimal(numericValue,0,5);
}

// }());