@extends('admin.layout.base')
@section('body')
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6">
<h3>Import Assembly Data</h3>
</div>
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
</ol>
</div>
</div> 
<div class="card">
<div class="card-body">
<form action="{{ route('admin.check.photo.quality.all.ac.store') }}" method="post" class="add_form" no-reset="true">
{{ csrf_field() }} 
<div class="row"> 
 <div class="col-lg-6 form-group">
    <label>District</label>
    <select name="district_id" class="form-control" multiselect-form="true" onchange="callAjax(this,'{{ route('admin.voter.districtWiseAssembly') }}','asembly_no_div')">
        <option selected disabled>Select District</option> 
        @foreach ($Districts as $District)
         <option value="{{ $District->id }}">{{ $District->code }}--{{ $District->name_e }}</option> 
        @endforeach 
     </select> 
 </div>
 <div class="col-lg-6 form-group">
    <label>Assembly Code</label>
    <select name="assembly" class="form-control" id="asembly_no_div" multiselect-form="true" onchange="callAjax(this,'{{ route('admin.database.conection.assemblyWisePartNo') }}','part_no_div')">
        <option selected disabled value = "0">Select Assembly Code</option> 
         
     </select> 
 </div>
  <div class="col-lg-12 form-group">
      <table class="table table-striped table-bordered">
          <thead>
              <tr>
                <td>
                 <div class="icheck-primary d-inline">
                 <input type="checkbox" id="all_check" class="checked_all">
                 <label for="all_check" class="checked_all">All</label>
                </td>
                  <th>Part No.</th>
                  <th>Total Import</th>
                  <th>Action</th>
              </tr>
          </thead>
          <tbody id="part_no_div">
              
          </tbody>
      </table>
  </div>
 
<div class="col-lg-12 form-group">
<button type="submit" class="btn btn-primary form-group form-control" >Submit</button>
</div>
</div>
</form> 
</div>
</div>  
</div>
</section>
@endsection 
@push('links')
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
@endpush
@push('scripts')
<script type="text/javascript" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">

$("#all_check").click(function () {
    $('input:checkbox').not(this).prop('checked', this.checked);
});

</script> 
@endpush



