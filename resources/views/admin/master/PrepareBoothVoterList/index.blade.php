@extends('admin.layout.base')
@section('body')
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6">
<h3>Booth Voter List</h3>
</div>
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
</ol>
</div>
</div> 
<div class="card">
<div class="card-body">
<form action="{{ route('admin.booth.voter.list.process') }}" method="post" class="add_form" no-reset="true">
{{ csrf_field() }} 
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
<label for="exampleInputEmail1">Block / MC's</label>
<span class="fa fa-asterisk"></span>
<select name="block" class="form-control select2" id="block_select_box" onchange="callAjax(this,'{{ route('admin.booth.voter.list.block.wise.booth.list') }}'+'?block_id='+this.value,'booth_list_div')">
 <option selected disabled>Select Block MCS</option> 
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
                  <th>Booth No.</th>
                  <th>Booth Name</th>
                  <th>With Photo</th>
                  <th>Without Photo</th>
              </tr>
          </thead>
          <tbody id="booth_list_div">
              
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

// setInterval(statusbarStart,5000);

// function statusbarStart() {
//     $.ajax({
//         {{-- url: '{{ route('admin.database.conection.process') }}', --}}
//         type: 'GET',

//     })
//     .done(function(response) {
//        $('#process').html(response)
//     })
//     .fail(function() {
//         console.log("error");
//     })
//     .always(function() {
//         console.log("complete");
//     });

//   {{-- callAjax(this,'{{ route('admin.database.conection.process') }}','process')   --}}

// }
//  setInterval(statusbarStart,1000);

//  $(document).ready(function(){ 
//     function statusbarStart(){
//       $.get('file.txt', function(data) {

//     });  
//     }

// });
$("#all_check").click(function () {
    $('input:checkbox').not(this).prop('checked', this.checked);
});

</script> 
@endpush



