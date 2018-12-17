<!DOCTYPE html>
<html lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ env('APP_NAME') }} Store Management</title>

        <!-- Bootstrap -->
        <link href="{{ asset("css/bootstrap.min.css") }}" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="{{ asset("css/font-awesome.min.css") }}" rel="stylesheet">
        <link href="{{ asset('vendors/datatables/dataTables.bootstrap.css') }}" rel="stylesheet">
        <link href="{{ asset('vendors/datatables/responsive.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('vendors/datatables/buttons.bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- Custom Theme Style -->
        <link href="{{ asset('css/gentelella.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/gdp.css') }}" rel='stylesheet'>


        @stack('stylesheets')

    </head>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">

                @include('includes/sidebar')

                @include('includes/topbar')
                @yield('main_container')

                @include('includes/footer')

            </div>
        </div>
        <div id='session_info' style="display:none">{{ json_encode($sessionInfo) }}</div>
        <div id="store_guardian" style="display:none">{{ json_encode($guardian) }}</div>            
        <div id="bodyLoader">
            <div id="loader"></div>
        </div>
        <!-- jQuery -->
        <script src='{{ asset("js/jquery.min.js") }}'></script>
        <!-- Bootstrap -->
        <script src='{{ asset("vendors/moment/moment-with-locales.min.js") }}'></script>                
        <script src='{{ asset("js/bootstrap.min.js") }}'></script>
        <!-- Custom Theme Scripts -->
        <script src='{{ asset("js/gentelella.min.js") }}'></script>
        <script src='{{ asset("vendors/datatables/jquery.dataTables.min.js") }}'></script>        
        <script src='{{ asset("vendors/datatables/dataTables.bootstrap.min.js") }}'></script>     
        {{-- disabled temporary until a.button bug resolved
          <script src='{{ asset("vendors/datatables/buttons.bootstrap.min.js") }}'></script>
          --}}     
        <script src='{{ asset("vendors/datatables/buttons.print.min.js") }}'></script>     
        <script src='{{ asset("vendors/datatables/dataTables.responsive.min.js") }}'></script>            
        <script src='{{ asset("js/jquery.validate.min.js") }}'></script>
        <script src='{{ asset("vendors/inputmask/jquery.inputmask.bundle.js") }}'></script>
        <script src='{{ asset("js/gdp.js") }}'></script>
        
        <script>
            initValidator();
            $('.decimal-two').inputmask('currency');
            $('.decimal-two-zero').inputmask('currency'); 
            $.validator.addMethod("decimal-two", function(value, element) {
                console.log({'element':element});

                return Number(convertDecimalSeparator(element.inputmask.unmaskedvalue()))>0;
                }, 
                "Please specify correct number."
            );

            $.validator.addClassRules("decimal-two",{"decimal-two":true});
            $.validator.addClassRules("money",{"decimal-two":true});
            app.init();
            function showLoader(){
                $('#bodyLoader').css({"display": "block"});
            }

            function hideLoader(){
                $('#bodyLoader').css({"display": "none"});
            }
        </script>
        @stack('scripts')

    </body>
</html>