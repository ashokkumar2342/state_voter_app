@extends('admin.layout.base')
@push('links')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">    
@endpush
@section('body')
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h3>Mapping Panchayat / MC's To Ward</h3>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right"> 
				</ol>
			</div>
		</div> 
		<div class="card card-info"> 
		    <div class="card-body">
		        <div class="btn-group col-lg-12">
		        	<button type="button" data-table-excel="village_sample_table" class="btn btn-primary" onclick="callAjax(this,'{{ route('admin.Master.MappingVillageTosample') }}','village_sample_list')">Excel Sample</button>
		        	<button type="button" class="btn btn-primary" onclick="callAjax(this,'{{ route('admin.Master.MappingVillageToForm') }}','village_sample_list')">Import</button> 
		        </div>
		        <div class="form-group col-lg-12 " id="village_sample_list" style="margin-top: 30px">
		         	
		        </div> 
		    </div>  
		</div>
	</div>	 
</section>
@endsection
@push('scripts') 
<script type="text/javascript" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js">
@endpush