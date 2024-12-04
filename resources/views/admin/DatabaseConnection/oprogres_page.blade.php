@php
	$percent = @$history->count / @$history->old_count * 100;
@endphp
Table Name : {{ @$history->table_name }}
<div class="progress"> 
<div class="progress-bar progress-bar-striped  progress-bar-animated active" role="progressbar"
aria-valuenow="{{ @$history->count }}" aria-valuemin="0" aria-valuemax="{{ @$history->old_count }}" style="width:{{ $percent }}%;background-color:#19ca19;font-size: 16px">{{ @$history->count }}/{{ @$history->old_count }} 
</div>
{{-- <span id="process_data" style="font-size: 16px">{{ @$history->count }}/</span> <span id="total_data" style="font-size: 16px"> {{ @$history->old_count }}</span> --}}
</div>
<script>
	
	jQuery(document).ready(function($) {
	  
	 var data=$('#count_{{ @$history->table_name }}').html({{ @$history->count }});
 
	});
	
</script> 