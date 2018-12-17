<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Emulating real sheets of paper in web documents (using HTML and CSS)">
        <title>{{ $reportTitle }}</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('vendors/necolas/normalize.css') }}" />  
        <link href="{{ asset("css/bootstrap.min.css") }}" rel="stylesheet">        
        <link href="https://fonts.googleapis.com/css?family=Noto+Serif|Nova+Mono" rel="stylesheet"> 
        <style>
            @media print {
                thead {
                    display: table-header-group;
                    page-break-after:avoid;
                }
                tbody{
                    orphans:3;                
                }
                .category-header{
                    page-break-inside:avoid;
                    page-break-after:avoid;                    
                }
                .block-category-table{
                    page-break-before:avoid;
                }
                #document{
                    padding: 0mm;
                    margin: 0mm;
                    border:none;
                }
                body{
                    font-family:"Noto Serif";                    
                    padding:0mm;
                }

            }
            @page        {
                /* You can only change the size, margins, orphans, widows and page breaks here */
                /* Paper size and page orientation */
                size: 210mm 297mm;                    

                /* Margin per single side of the page */
                margin:18mm 10mm 18mm 10mm;                    
                padding:0px;
            }            
            @media screen{
                #document{
                    padding: 5mm;
                    margin:18mm 10mm 18mm 10mm;                    
                    border: 0.5mm solid brown;
                    width: 210mm; 
                }
                body{
                    font-family:"Noto Serif";
                    padding: 5mm;
                }
            }
            table{
                border-collapse:collapse;
                border-spacing: 0mm;  
                width:100%;   
            }
            tbody{
                font-family:'Nova Mono', monospace;
                font-size:9pt;
                font-weight: 700;
            }
            thead{
                font-size:10pt;
                text-align:center;
            }
            td,th{
                border: 0.5mm solid black;
                padding: 0.5mm 1mm;
                text-align: center;
            }

            th {
                text-align: center;
            }
            
            

            #loader {
              position: absolute;
              left: 50%;
              top: 50%;
              z-index: 1;
              width: 150px;
              height: 150px;
              margin: -75px 0 0 -75px;
              border: 16px solid #f3f3f3;
              border-radius: 50%;
              border-top: 16px solid #3498db;
              width: 120px;
              height: 120px;
              -webkit-animation: spin 2s linear infinite;
              animation: spin 2s linear infinite;
            }

            @-webkit-keyframes spin {
              0% { -webkit-transform: rotate(0deg); }
              100% { -webkit-transform: rotate(360deg); }
            }

            @keyframes spin {
              0% { transform: rotate(0deg); }
              100% { transform: rotate(360deg); }
            }

            /* Add animation to "page content" */
            .animate-bottom {
              position: relative;
              -webkit-animation-name: animatebottom;
              -webkit-animation-duration: 1s;
              animation-name: animatebottom;
              animation-duration: 1s
            }

            @-webkit-keyframes animatebottom {
              from { bottom:-100px; opacity:0 } 
              to { bottom:0px; opacity:1 }
            }

            @keyframes animatebottom { 
              from{ bottom:-100px; opacity:0 } 
              to{ bottom:0; opacity:1 }
            }
        </style>
        @stack('stylesheets')
    </head>
    <body>

        <div id='document'>
            <!-- <div id='msg_box'>Sedang proses...</div> -->
            <div id="loader"></div>
        </div>

        <div id='report_data' style='display:none;'></div>
        <div id='report_parameters' style='display:none'>{{$strReportParam}}</div>
        <script src='{{ asset("vendors/moment/moment-with-locales.min.js") }}'></script>
        <script src='{{ asset("vendors/moment/moment-timezone-with-data.min.js") }}'></script>
        <script src='{{ asset("js/jquery.min.js") }}'></script>
        <script src='{{ asset("vendors/inputmask/jquery.inputmask.bundle.js") }}'></script>    
        <script src='{{ asset("js/gdp.js") }}'></script>    
        @stack('scripts')
        
    </body>
</html>
