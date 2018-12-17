<script type="text/javascript">

/**
 * convert array to object which element is from array element
 * @param  {[type]} objData [description]
 * @return {[type]}         [description]
 */
const arrToObject= function(arrData){
    return arrData.reduce(
        function(acc, cur, i) {
            acc[i] = cur;
            return acc;
        }, {}
    );
};


/**
 * construct row of table
 * @param  {array} arrCellData 
 * @return {string} string contain html tag of row
 */
const constructRow = function (arrCellData){
    strRes = '<tr>';
    for(cellData of arrCellData){
        strRes += '<td>' + cellData + '</td>';
    }
    strRes += '</tr>'
    return strRes;
};

/**
 * [convertDecimalSeparator description]
 * @param  {string} strNumber number with decimal separator
 * @return {string}           number with US decimal separator
 * input ex: 2344,35
 * outptu ex: 2344.35
 */
const convertDecimalSeparator= function (strNumber){
    return strNumber.replace(',','.');

};

const convertDecimalSeparatorTo= function (strNumber,strFrom,strTo){
    return strNumber.replace(strFrom, strTo);

};

const  displayMessage = function(objData){
    console.log(objData);        
    strMessage = '<p>'+objData.message+'</p>';
    
    if ( objData.logs!=null){
        arrLogs = objData.logs;
        strLogs = '<p>';
        for (let logItem of arrLogs){
            strLogs += JSON.stringify(logItem)+'<br>';
        }
        strLogs += '</p>';
    } else {
        return 0;
    }

    if (objData.statuscode<0) {
        $('#err_message').html(strMessage+strLogs);
        $('#err_message').show();
    } else {
        $('#info_message').html(strMessage+strLogs);
        $('#info_message').show();            
    }
};

const fetchData= function( strUrl, objParam){
    console.log('fetching...');
    if (objParam==null){
        objParam = {};
    }
    objParam['_token']=$("#token").val();
    return Promise.resolve($.post(strUrl, objParam));
};

/**
 *Get object having id in array of object
 *@param {array object} arrObject 
 *@param {int} id
 *@return { object } obj,  
 */
//

const  findInArrObject = function(arrObject,id){
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

//input: 
//  array of object
//  object of criteria, example
//      {name:'john', age:20}
//output: array of object satisfy criteria
const getArrFilteredObject = function (arrData, objCriteria){
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


/**
 * abstraction to get data from element
 * accepted 1st parameter:
 * elm tag: div, input
 * type: string
 * output: obj/arr
 */
const getLocalJson= function(strElmId){
    res = [];
    if(typeof strElmId != 'string'){
        console.log('getLocalData: 1st parameter must be string');
        return '';
    }    
    targetElm = $('#'+strElmId);
    tagName = targetElm.prop('tagName');
    //check if id exist
    if (targetElm.length == 0) {
        console.log('getLocalData: id not exist '+strElmId);
        return [];
    }
    data = '';
    //for div get html content
    if ( tagName == 'DIV'){
        data = targetElm.html();
    }
    //for tag input
    if ( tagName == 'INPUT'){
        data =  targetElm.val();
    }

    if (data==''){
        console.log('getLocalJson: empty element.');
        return [];
    }

    //try to parse
    try{
        res = JSON.parse(data);
    }
    catch (e){
        console.log('getLocalData: err parse json.');
    }

    return res;
};


/*
input: - 
output: string current date in format of yyyy-mm-dd, ex: 2017-02-24
 */
const getStrTodayDate= function(){
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
const objToArray= function(objData){
     return Object.keys(objData).map((key)=>objData.key);
};

const removeElement = function(arrData, id){
        x = findInArrObject(arrData, id);
        pos = arrData.indexOf(x);
        arrData.splice(pos, 1);
        console.log(arrData);
};

const setCbo = function(strTargetCbo, arrData, propForValue, propForContent, strValueSelected){
    var elmTargetCbo = $('#'+strTargetCbo);
    if (propForValue == undefined){
        propForValue = 'id';
    }
    if (propForContent == undefined){
        propForContent = 'name';
    }
    elmTargetCbo.html('');
    for (let objData of arrData) {
        var strHTML = '<option value="'+objData[propForValue]+'">'+objData[propForContent]+'</option>';
        elmTargetCbo.append(strHTML);
    }
    if(typeof  strValueSelected !== 'undefined'){
        elmTargetCbo.val( strValueSelected)
    }
};


const setCboFromDiv = function(strSourceElm, strTargetCbo, propForValue, propForContent){
    var arrData = getLocalJson(strSourceElm);
    console.log(['setcbofromdiv',arrData]);
    setCbo(strTargetCbo, arrData, propForValue, propForContent);
};

const setCboSelectedOption= function(){

};

const setCboFromServer= function( strTargetCbo, strUrl, objParam, strBlankValue=''){

    return fetchData(strUrl, objParam).
    then(function (objData) {
        if(strBlankValue!==''){
            objData.data.push({'id':0,'name':strBlankValue});
        }
        setCbo(strTargetCbo, objData.data);
    });
};

//abstraction to set var data to element
//accepted input 1 parameter:
//  elm tag: div, input
//  type: string
//  output: boolean
const setLocalJson= function(data, strElmId){
    if(typeof strElmId != 'string'){
        return false;
    }
    try{
        strData = JSON.stringify(data);
    }
    catch(e){
        return false;
    }
    targetElm = $('#'+strElmId);
    tagName = targetElm.prop('tagName');
    //check if id exist
    if (targetElm.length == 0) {
        return false;
    }    

    if ( tagName == 'DIV'){
        targetElm.html(strData);
    //for tag input
    } else if ( tagName == 'INPUT'){
        targetElm.val(strData);
    } else {
        return false;
    }

    return true;

};



</script>