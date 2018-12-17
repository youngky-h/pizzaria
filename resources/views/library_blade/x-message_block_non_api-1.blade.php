            @if(Session::has('statusCode'))
                @if(Session::get('statusCode') < 0)
                    <div class="alert alert-danger">
                        {{ Session::get('message') }}
                        @php 
                            dump(Session::get('logs')); 
                        @endphp

                    </div>
                @elseif (Session::get('statusCode') > 0)
                    <div class="alert alert-success">
                        {{ Session::get('message') }}
                        @php 
                            dump(Session::get('logs')); 
                            
                        @endphp
                    </div>
                @endif
            @endif
            @isset($statusCode)
                @if($statusCode < 0)
                    <div class="alert alert-danger">
                        {{ $message }}
                        @php 
                            dump($logs) 
                        @endphp

                    </div>
                @elseif ($statusCode > 0)
                    <div class="alert alert-success">
                        {{ $message }}
                        @php 
                            dump($logs)
                        @endphp
                    </div>
                @endif
            @endisset
