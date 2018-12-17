<div class='form-group'>
    <label>{{ $config['componentLabel'] }}</label>
		
    <input type="text" class="form-control"  
    @isset($config['componentId'])
    	id={{ $config['componentId'] }}
    @endisset
    @isset($config['componentName'])
    	name={{ $config['componentName'] }} 
    @endisset
		required 
	/>
</div>