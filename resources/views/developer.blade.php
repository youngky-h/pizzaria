@extends('layouts.blank')

@push('stylesheets')
    <!-- Example -->
    <link href="{{ asset('vendors/datatables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/fileinput/fileinput.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('vendors/datatables/responsive.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link  href="{{ asset('vendors/jquery-cropper/cropper.css')}}" rel="stylesheet">
    <style>
        #create_preview{
            max-width:90%;
        }
        .image_wrapper{
            width:350px;
            height:350px;
        }
    </style>
@endpush

@push('scripts')
    <script src='{{ asset("vendors/datatables/jquery.dataTables.js") }}'></script>
    <script src='{{ asset("vendors/datatables/dataTables.bootstrap.js") }}'></script>
    <script src="{{ asset('vendors/fileinput/fileinput.js') }}" type="text/javascript"></script>
    <script src='{{ asset("vendors/inputmask/jquery.inputmask.bundle.js") }}'></script>
    <script src='{{ asset("vendors/datatables/dataTables.responsive.min.js") }}'></script>    
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.4.1/cropper.js"></script> -->
    <script src="{{ asset('vendors/jquery-cropper/cropper.js') }}"></script>
    <script src="{{ asset('vendors/jquery-cropper/jquery-cropper.js') }}"></script>
    <script type='text/javascript'>

    $(document).ready(function(){
        // $('#tbl_main').dataTable(); 
        // $('#tbl_search').dataTable();
       
    });

    </script>
@endpush

@section('main_container')

    <!-- page content -->
    <div class="right_col" role="main">
    @include('library_blade.row.msg-1')
        <div class="page-title">
            <div class="title_left">
                <h3>Debug Tool</h3>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class='row'>
            <div class="col-xs-12">
                <div class='x_panel'>
                    <div class="x_title">
                        <h3>Debugging..</h3>
                    </div>
                    <div class="clearfix"></div>
                    <div class="x_content">
                        <div class='form-group'>
                            <input type='text' class="form-control" id='request_url' value='test_controller.php/responder'>
                        </div>              
                        <div class='form-group'>
                            <textarea class="form-control" placeholder="obj param" id='ta_param'></textarea>
                        </div>                
                        <div>
                            <input id="img_test" type="file" name='img_test' class="file file-loading" 
                            multiple='true' data-allowed-file-extensions='["jpg", "png"]' data-show-upload="false">
                        </div> 
                        <div class='form-group'>
                            <button id='btn_request' type="button" class="form-control">request</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h3>Variables</h3>
                    </div>
                    <div class='x_content'>             
                        <h4>Result Vars (Dump)</h4>   
                        <p>@php
                            dump($resDump)
                            @endphp
                        </p>
                        <h4>Guardian (Dump)</h4>   
                        <p>@php
                            dump($guardian)
                            @endphp
                        </p>
                        <h4>Session Info (Dump)</h4>   
                        <p>@php
                            dump($sessionInfo)
                            @endphp
                        </p>                                                
                        <h4>Result Vars (Print_R)</h4>                
                        <p>
                            <pre><code>
                            @php
                            var_dump($resPrint)
                            @endphp
                            </code></pre>
                        </p>                    
                        <h4>Declared Vars</h4>                
                        <p>
                        </p>

                        <h4>Old input</h4>
                        <p> @php
                            dump(old())
                            @endphp
                        </p>

                        <h4>Daftar Variabel Session</h4>
                        <p> @php
                            dump(session())
                            @endphp
                        </p>                    
                        <h4>Daftar Variabel Request</h4>                
                        <p>
                            @php
                            @endphp
                        </p>                     
                    </div>
                    
                </div>
            </div>
            
        </div>        
        <div id=storageSelectedRow></div>
    </div>
    @push('scripts')
    <script type='text/javascript'>
        $(document).ready(function() {
            var p = 'test';
            const arrRequest = [
                {
                    name='login',
                    param='{"platform":"web","version":"1","data":{"username":"Regina","password":"123456"}}',
                    url='api/funct/login'
                },
                {
                    name='fetchBranchData',
                    param = '{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjIsImlzcyI6Imh0dHA6Ly9kZXYubWFjYW4ubG9jYWwvYXBpL2Z1bmN0L2xvZ2luIiwiaWF0IjoxNTE5MjA2NDgyLCJleHAiOjE1MTkyMTAwODIsIm5iZiI6MTUxOTIwNjQ4MiwianRpIjoiU3VpbXJheW5YZ0t3YzZPRyJ9.033X2-uJeSeiLa9Kz05gAGI3qfCqHWMCatLlu-yr0-I"}',
                    url = 'api/funct/branch/fetch_list_enabled'
                }
                
            ];

            $('#btn_request').click(function(){
                id = $('#request_url').val();
                strUrl = loginUrl;
                objParam = convertToObject(loginData);
                console.log({"par":objParam});
                x = sendRequest(strUrl, objParam )
            });

            token = $("#token").val();
            result = '';
            // $("#tbl_example").dataTable({
            //     data: result,
            //     // ajax: {
            //     //     url: result,

            //     // },
            //     columns: [
            //         { title:'Id', data: 'id' },
            //         { title:'Name', data: 'name' },
            //         { title:'Email', data: 'email'},
            //         { title:'Handphone', data: 'handphone'},    
            //         { title:'Action', data: 'action'}
            //     ]
            // });                


        });

        function appendData(){

        }


        
        function ajaxTest(){
            var requests = [ //this will be the dynamic part
              $.get('page1.php'),
              $.get('page2.php'),
              $.get('page3.php')
            ]
             
            $.when.apply($,requests).done(function(){
              console.log(arguments); //array of responses [0][data, status, xhrObj],[1][data, status, xhrObj]...
            })

        }

        function showFail(){
            alert('failed');
        }
        function convertToObject(strData){
            res='';
            try{
                // res = JSON.parse($('#ta_param').val())
                res = JSON.parse(strData);
                console.log({'res':res});
                res.data = JSON.stringify(res.data);
                console.log({'res':res});
            }
            catch(e){
                console.log({'convert err':res});
                res = {};
            }
            return res;
        }
        function setTableHeading(tblId, arrData){
            $('#'+tblId+'>thead').html('');
        }

        function objToHtml(mightBeObj){
            if(typeof mightBeObj == 'object'){
                data = '<ul>';
                for (att in mightBeObj){
                    if(typeof mightBeObj[att] == 'object'){
                        data += objToHtml(mightBeObj[att]);
                    } else {
                        data += '<li>'+mightBeObj[att]+'</li>';
                    }
                }
                data += '</ul>';
            } else {
                console.log({'obj is':typeof data});
                data = mightBeObj;
            }
            return data;
        }
        function sendRequest(strUrl, objParam){
            objParam['_token'] = $("#token").val();
            console.log({'send':objParam});
            return $.post(strUrl, objParam,
                function(data){
                    container = objToHtml(data);
                    $('#response_container').html(container);

                }
            ).fail(
                function(data){
                    console.log('err');
                    $('#response_container').html(data.responseText);
                }

            );
        };
    </script>
    @endpush
@endsection
    <!-- /page content -->
