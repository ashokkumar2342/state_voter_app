@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Add New Voter</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <button type="button" class="hidden" hidden="hidden" id="btn_refresh" onclick="window.location.reload();">refresh</button>
                </ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body"> 
                <form action="{{ route('admin.voter.details.store') }}" method="post" class="add_form" button-click="btn_refresh">
                    {{ csrf_field() }} 
                    <div class="row">  
                        <div class="col-lg-3 form-group">
                            <label for="exampleInputEmail1">District</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('admin.voter.districtWiseAssembly') }}','assembly_select_box');Hide_Form();">
                                <option selected disabled>Select District</option>
                                @foreach ($rs_district as $rs_val)
                                    <option value="{{ Crypt::encrypt($rs_val->opt_id) }}">{{ $rs_val->opt_text }}</option>  
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 form-group">
                            <label for="exampleInputEmail1">Assembly</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="assembly" class="form-control select2" id="assembly_select_box" select-triger="part_no_select_box" onchange="callAjax(this,'{{ route('admin.Master.AssemblyWiseAllPartNo') }}','part_no_select_box');Hide_Form();">
                                <option selected value="{{ Crypt::encrypt(0) }}">Select Assembly</option>
                                 
                            </select>
                        </div>
                        <div class="col-lg-3 form-group">
                            <label for="exampleInputEmail1">Part No.</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="part_no" class="form-control select2" id="part_no_select_box" onchange="Hide_Form();">
                                <option selected value="{{ Crypt::encrypt(0) }}">Select Part No.</option> 
                            </select>
                        </div>
                        <div class="col-lg-3 form-group">
                            <label for="exampleInputEmail1">Sr. No. in Part</label> 
                            <span class="fa fa-asterisk"></span>
                            <input type="text" name="srno_part" id="srno_part" class="form-control" maxlength="4" onkeypress='return event.charCode >= 48 && event.charCode <= 57' required onblur="callAjax(this, '{{ route('admin.voter.details.form') }}'+'?part_no='+$('#part_no_select_box').val()+'&srno_part='+$('#srno_part').val(), 'detailEntryForm');Show_Form();" onfocus="Hide_Form();">
                        </div>
                    </div>
                    <div class="row" id="detailEntryForm">
                    </div>
                 </form>
             </div>
         </div>
         <div class="card card-info"> 
            <div class="card-body">
                <div class="row" id="checkDuplicateRecord"></div>
            </div>
        </div>
        <div class="card card-info"> 
            <div class="card-body">
                <div class="row" id="checkDuplicateRecord1"></div>
            </div>
        </div>
        <div class="card card-info"> 
            <div class="card-body">
                <div class="row" id="voter_list_table"></div>
            </div>
        </div>
     </div>
</section>
@endsection
@push('scripts')

<script>
    function EmpNameFill(val, con_type) {
        if (con_type == 1) {
            $("#name_local_language").val(val);
        }
        if (con_type == 2) {
            $("#f_h_name_local_language").val(val);
        }
        if (con_type == 3) {
            $("#house_no_local_language").val(val);
        }
        $("#btn_close").click();
    }

    function Hide_Form() {
        $("#detailEntryForm").hide();
        $("#checkDuplicateRecord").hide();
        $("#checkDuplicateRecord1").hide();
    }
    function Show_Form() {
        $("#detailEntryForm").show();
        $("#checkDuplicateRecord").show();
        $("#checkDuplicateRecord1").show();
    }
</script>


@endpush

