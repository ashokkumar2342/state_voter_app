@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Assembly Parts</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card card-primary"> 
                            <form action="{{ route('admin.Master.AssemblyPart.store') }}" method="post" class="add_form" no-reset="true" select-triger="assembly_select_box" button-click="btn_click_part_no_div">
                                {{ csrf_field() }}
                                <div class="card-body"> 
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">District</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="district" class="form-control" id="district_select_box" onchange="callAjax(this,'{{ route('admin.voter.districtWiseAssembly') }}','assembly_select_box');callAjax(this,'{{ route('admin.Master.AssemblyPartTable') }}'+'?assembly_id='+$('#assembly_select_box').val(),'part_no_table')">
                                            <option selected disabled>Select District</option>
                                            @foreach ($Districts as $District)
                                            <option value="{{ $District->id }}">{{ $District->code }}--{{ $District->name_e }}</option>  
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Assembly</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="assembly" class="form-control" id="assembly_select_box" data-table="part_no_datatable" onchange="callAjax(this,'{{ route('admin.Master.AssemblyPartTable') }}'+'?assembly_id='+$('#assembly_select_box').val(),'part_no_table')">
                                            <option selected disabled>Select Assembly</option>
                                             
                                        </select>
                                    </div>
                                <button type="button" hidden id="btn_click_part_no_div" onclick="callAjax(this,'{{ route('admin.Master.AssemblyPartbtnclickBypartNo') }}','btn_click_part_no_page')"> 
                                </button>
                                <div id="btn_click_part_no_page">
                                      
                                </div>  
                                    
                                </div> 
                                <div class="card-footer text-center">
                                    <button type="submit" id="" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div> 
                    </div>
                    <div class="col-lg-6">
                        <div class="card card-primary table-responsive" id="part_no_table"> 
                             <table id="part_no_datatable" class="table table-striped table-hover control-label">
                                 <thead>
                                     <tr>                                          
                                         <th>Part No.</th> 
                                         <th>Action</th>
                                          
                                     </tr>
                                 </thead>
                                 <tbody>
                                    
                                 </tbody>
                             </table>
                        </div> 
                    </div> 
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
     $(document).ready(function(){
        $('#btn_click_part_no_div').click();
        $('#part_no_datatable').DataTable();
    });
</script> 
@endpush