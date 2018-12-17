<div class="row">

    <div class="col-md-12 col-sm-12 col-xs-12">
        @if(Session::has('statusCode'))
            @if(Session::get('statusCode') < 0)
                <div class="alert alert-danger">
            @elseif (Session::get('statusCode') > 0)
                <div class="alert alert-success">
            @endif
                    {{ Session::get('message') }}
                </div>
        @endif

        @isset($statusCode)
            @if($statusCode < 0)
                <div class="alert alert-danger">
                    {{ $message }}
                </div>
            @elseif ($statusCode > 0)
                <div class="alert alert-success">
                    {{ $message }}
                </div>
            @endif
        @endisset
        @if(env('APP_DEBUG',false))
            @isset($logs)
            <div class='alert alert-warning'>
                @php  
                    dump($logs);
                @endphp
            </div>
            @endisset

            @if(!empty(Session::get('logs')))
            <div class='alert alert-warning'>
                    @php dump(Session::get('logs')) @endphp
            </div>
            @endif

            @isset($xtra)
            <div class='alert alert-warning'>
                @php
                    dump($xtra);
                    dump(session());
                @endphp
            </div>
            @endisset
        @endif
        <div style='display:none;word-break:break-all;' id='err_message' class="alert alert-danger">Error
        </div>
        <div style='display:none;word-break:break-all;' id='info_message' class="alert alert-success">Info
        </div>
        <style>
            pre.sf-dump{
                z-index:1;
            }
        </style>        
    </div>
</div>      