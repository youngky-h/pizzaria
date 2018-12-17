            @if(Session::has('statusCode'))
                @if(Session::get('statusCode') < 0)
                    <div class="alert alert-danger">
                        {{ Session::get('message') }}
                        @php 
                            dump(Session::get('errors')); 
                        @endphp

                    </div>
                @elseif (Session::get('statusCode') > 0)
                    <div class="alert alert-success">
                        {{ Session::get('message') }}
                        @php 
                            dump(Session::get('errors')); 
                            
                        @endphp
                    </div>
                @endif
            @endif
