<div class="col-lg-12">
    <fieldset class="fieldset_border">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="result_table">
                <thead class="thead-dark">
					<tr>
						<th>AC No. <br> Part No. <br> Sr. No.</th>
						<th>Name<br>F/H Name<br>Gender</th>
						<th>EPIC No.<br>House No.<br>Age</th>
						<th>MC<br>Ward No.<br>Booth No.</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($voters as $voter)
						<tr>
							<td>{{$voter->ac_name}}<br>{{$voter->part_no}}<br>{{$voter->sr_no}}</td>
							<td>{{$voter->voter_name}}<br>{{$voter->father_name_e}}<br>{{$voter->genders}}</td>
							<td>{{$voter->voter_card_no}}<br>{{$voter->house_no_e}}<br>{{$voter->age}}</td>
							<td>{{$voter->v_name}}<br>{{$voter->ward_no}}<br>{{$voter->booth_no}}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</fieldset>
</div>