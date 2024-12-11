<div class="col-lg-12">
    <fieldset class="fieldset_border">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="ajax_data_table">
                <thead class="thead-dark">
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
        </div>
    </fieldset>
</div>