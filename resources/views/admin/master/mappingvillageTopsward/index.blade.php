@extends('admin.layout.base')
@section('body')
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-9">
				<h3>Mapping (Panchayat Wards - Panchayat Samiti Ward) </h3>
			</div>
			<div class="col-sm-3">
				<ol class="breadcrumb float-sm-right"> 
				</ol>
			</div>
		</div> 
		<div class="card card-info"> 
			<div class="card-body"> 
				<form action="{{ route('admin.Master.MappingVillageToPSWardStore') }}" method="post" class="add_form" no-reset="true">
					{{ csrf_field() }}
					<div class="card-body row">
						<div class="col-lg-3 form-group">
                        <label for="exampleInputEmail1">State</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="states" id="state_id" class="form-control select2" onchange="callAjax(this,'{{ route('admin.Master.stateWiseDistrict') }}','district_select_box')">
                        <option selected disabled>Select States</option>
                        @foreach ($States as $State)
                        <option value="{{ $State->id }}">{{ $State->code }}--{{ $State->name_e }}</option>  
                        @endforeach
                        </select>
                        </div>
                        <div class="col-lg-3 form-group">
                        <label for="exampleInputEmail1">District</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock') }}','block_select_box')">
                        <option selected disabled>Select District</option>
                        </select>
                        </div>
                        <div class="col-lg-3 form-group">
                        <label for="exampleInputEmail1">Block / MC's</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="block" class="form-control select2" id="block_select_box" onchange="callAjax(this,'{{ route('admin.Master.blockwisePsWard') }}','ps_select_box')">
                            <option selected disabled>Select Block / MC's</option> 
                        </select>
                        </div>
						<div class="col-lg-3 form-group">
							<label for="exampleInputEmail1">Panchayat Samiti Ward</label>
							<span class="fa fa-asterisk"></span>
							<select name="ps_ward" duallistbox="true" class="form-control" id="ps_select_box" onchange="callAjax(this,'{{ route('admin.Master.BlockOrPSwardWiseVillage') }}'+'?block_id='+$('#block_select_box').val(),'village_ward_table')">
								<option selected disabled>Select P.S. Ward</option>
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

