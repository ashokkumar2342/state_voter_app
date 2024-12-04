@extends('admin.layout.base')
@section('body')
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-9">
				<h3>Mapping (Panchayats To Z.P.Ward)</h3>
			</div>
			<div class="col-sm-3">
				<ol class="breadcrumb float-sm-right"> 
				</ol>
			</div>
		</div> 
		<div class="card card-info"> 
			<div class="card-body"> 
				<form action="{{ route('admin.Master.MappingVillageToZPWardStore') }}" method="post" class="add_form" no-reset="true">
					{{ csrf_field() }}
					<div class="card-body row">
						<div class="col-lg-4 form-group">
							<label for="exampleInputEmail1">State</label>
							<span class="fa fa-asterisk"></span>
							<select name="states" class="form-control"  onchange="callAjax(this,'{{ route('admin.Master.stateWiseDistrict') }}','district_select_box')" required>
								<option selected disabled>Select States</option>
								@foreach ($States as $State)
								<option value="{{ $State->id }}">{{ $State->code }}--{{ $State->name_e }}</option>  
								@endforeach
							</select>
						</div>
						<div class="col-lg-4 form-group">
							<label for="exampleInputEmail1">District</label>
							<span class="fa fa-asterisk"></span>
							<select name="district" class="form-control" id="district_select_box" data-table="zp_ward_datatable" onchange="callAjax(this,'{{ route('admin.Master.districtwiseZPWard') }}'+'?district_id='+$('#district_select_box').val(),'zp_select_box')" required>
								<option selected disabled>Select District</option>
							</select>
						</div>  
						<div class="col-lg-4 form-group">
							<label for="exampleInputEmail1">Zila Parishad Ward</label>
							<span class="fa fa-asterisk"></span>
							<select name="zp_ward" duallistbox="true" class="form-control" id="zp_select_box" onchange="callAjax(this,'{{ route('admin.Master.districtOrZpwardWiseVillage') }}'+'?district_id='+$('#district_select_box').val(),'village_ward_table')" required>
								<option selected disabled>Select Z.P. Ward</option>
							</select>
						</div>
						<div class="col-lg-12" id="village_ward_table">
						 	
						 </div> 
					</div> 
					<div class="card-footer text-center">
						<button type="submit" class="btn btn-primary form-control">Submit</button>
					</div>
				</form> 
			</div> 
		</div>
	</div>	 
	</section>
	@endsection
	@push('scripts')
	<script type="text/javascript">
		$('#district_datatable').DataTable();
	</script>
	@endpush 

