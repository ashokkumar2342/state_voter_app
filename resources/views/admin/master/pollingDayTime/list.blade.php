<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Polling Day Time (In English)</th>
			<th>Polling Day Time (In Hindi)</th>
			<th>Sign Stamp</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($PollingDayTimes as $PollingDayTime)
					
		<tr>
			<td>{{ $PollingDayTime->polling_day_time_e }}</td>
			<td>{{ $PollingDayTime->polling_day_time_l }}</td>
			
			<td>
				<img width="200px" src="{{ route('admin.Master.pollingDayTimesignature',Crypt::encrypt($PollingDayTime->signature)) }}"  alt="" title="" />
			</td>
			
		</tr> 
		@endforeach
	</tbody>
</table>