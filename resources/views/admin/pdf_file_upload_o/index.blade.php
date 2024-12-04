@extends('admin.layout.base')
@section('body')
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h3>PDF File Upload</h3>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right"> 
				</ol>
			</div>
		</div> 
		<div class="card card-info"> 
			<div class="card-body"> 
				<form action="{{ route('admin.PDFFileUpload.Upload') }}" method="post" no-reset="true" target="blank">
					{{ csrf_field() }}
					<div class="card-body">
						<div class="row">  
							<div class="col-lg-6 form-group">
								<label for="exampleInputEmail1">District</label>
								<span class="fa fa-asterisk"></span>
								<select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock') }}','block_select_box')">
									<option selected disabled>Select District</option>
									@foreach ($Districts as $District)
									<option value="{{ $District->id }}">{{ $District->code }}--{{ $District->name_e }}</option>  
									@endforeach
								</select>
							</div>
							<div class="col-lg-6 form-group">
								<label for="exampleInputEmail1">Block MCS</label>
								<span class="fa fa-asterisk"></span>
								<select name="block" class="form-control select2" id="block_select_box">
									<option selected disabled>Select Block MCS</option> 
								</select>
							</div>
							<div class="col-lg-12 form-group">
								<label for="exampleInputEmail1">File Path</label>
								 <input type="text" name="file_path" class="form-control">
							</div>
							 
							<div class="col-lg-12 form-group">
								<input type="submit" value="Upload" class="btn btn-primary form-control">
							</div>
								  
						</div>
					</div>
				</form>
			</div>
		</div>
	</div> 
</section>
@endsection
@push('scripts')

@endpush


