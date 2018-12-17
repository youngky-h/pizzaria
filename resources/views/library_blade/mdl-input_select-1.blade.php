<div class='form-group'>
    <label>{{ $config['componentLabel'] }}</label>
    <select class='form-control'
        @isset($config['componentId'])
            id={{ $config['componentId'] }}
        @endisset
        @isset($config['componentName'])
            name={{ $config['componentName'] }} 
    @endisset
    >
    </select>
</div>