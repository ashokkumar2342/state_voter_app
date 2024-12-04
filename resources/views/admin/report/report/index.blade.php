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
                <h3>Report</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body">
              <form action="{{ route('admin.report.ReportGenerate') }}" method="post" class="add_form" success-content-id="result_data" data-table-without-pagination="village_ward_sample_table" no-reset="true">
                {{csrf_field()}} 
                <div class="row">
                  <div class="col-lg-4 form-group">
                  <label for="exampleInputEmail1">District</label> 
                  <select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock') }}','block_select_box');
                  callAjax(this,'{{ route('admin.voter.districtWiseAssembly') }}','assembly_select_box');
                  callAjax(this,'{{ route('admin.report.destrict.wise.zp.ward') }}','zp_ward_select_box');
                  callAjax('','{{ route('admin.Master.BlockWiseVillage') }}'+'?id=0','village_select_box');
                  callAjax('','{{ route('admin.report.block.wise.ps.ward') }}'+'?id=0','ps_ward_select_box');
                  callAjax('','{{ route('admin.report.village.wise.booth') }}'+'?id=0','booth_select_box');
                  callAjax('','{{ route('admin.voter.VillageWiseWard') }}'+'?id=0','ward_no_select_box');
                  callAjax('','{{ route('admin.voter.AssemblyWisePartNo') }}'+'?id=0','part_no_select_box')">
                  <option selected disabled value="0">Select District</option>
                  @foreach ($Districts as $District)
                    <option value="{{$District->id}}">{{$District->name_e}}</option>
                  @endforeach
                  </select>
                  </div>

                  <div class="col-lg-4 form-group">
                  <label for="exampleInputEmail1">Block / MC's</label> 
                  <select name="block" class="form-control select2" id="block_select_box" onchange="callAjax(this,'{{ route('admin.Master.BlockWiseVillage') }}','village_select_box');
                  callAjax(this,'{{ route('admin.report.block.wise.ps.ward') }}','ps_ward_select_box');
                  callAjax('','{{ route('admin.report.village.wise.booth') }}'+'?id=0','booth_select_box');
                  callAjax('','{{ route('admin.voter.VillageWiseWard') }}'+'?id=0','ward_no_select_box');">
                      <option selected disabled value="0">Select Block / MC's</option> 
                  </select>
                  </div>


                  <div class="col-lg-4 form-group">
                      <label for="exampleInputEmail1">Panchayat / MC's</label> 
                      <select name="village" class="form-control select2" id="village_select_box" select2="true" onchange="callAjax(this,'{{ route('admin.voter.VillageWiseWard') }}','ward_no_select_box');
                      callAjax(this,'{{ route('admin.report.village.wise.booth') }}','booth_select_box')">
                          <option selected disabled>Select Panchayat / MC's</option> 
                      </select>
                  </div>


                  <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">Ward No.</label> 
                            <select name="ward_no" class="form-control select2" id="ward_no_select_box" >
                                <option selected disabled>Select Ward No.</option> 
                            </select>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">Assembly</label> 
                            <select name="assembly" class="form-control select2" id="assembly_select_box" onchange="callAjax(this,'{{ route('admin.voter.AssemblyWisePartNo') }}','part_no_select_box')">
                                <option selected disabled>Select Assembly</option> 
                            </select>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">Part No.</label> 
                            <select name="part_no" class="form-control select2" id="part_no_select_box">
                                <option selected disabled>Select Part No.</option> 
                            </select>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">Booth No.</label> 
                            <select name="booth" class="form-control select2" id="booth_select_box">
                                <option selected disabled>Select Booth No.</option> 
                            </select>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">Z.P Ward</label> 
                            <select name="zp_ward" class="form-control select2" id="zp_ward_select_box">
                                <option selected disabled>Select Z.P Ward</option> 
                            </select>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">P.S Ward</label> 
                            <select name="ps_ward" class="form-control select2" id="ps_ward_select_box">
                                <option selected disabled>Select P.S Ward</option> 
                            </select>
                        </div>
                  <div class="col-lg-4 form-group">
                    <label>Report Type</label>
                    <select name="report_type_id" id="report_type_id" class="form-control">
                      <option selected disabled>Select Report Type</option>
                      @foreach ($reportTypes as $reportType)
                      <option value="{{ $reportType->id }}">{{ $reportType->name }}</option>
                      @endforeach 
                    </select> 
                  </div>
                  <input type="hidden" name="submit_type" id="submit_type" value="0">
                  <div class="col-lg-4 form-group" style="margin-top: 30px">
                                       <input type="submit" class="btn btn-primary form-control" value="Get Report in Excel" onclick="$('#submit_type').val(1)">
                                   </div>
                                  {{--  <div class="col-lg-4 form-group" style="margin-top: 30px">
                                       <input type="submit" class="btn btn-warning form-control" value="Get Report in PDF" onclick="$('#submit_type').val(2)">
                                   </div> --}}

                  
                 </div>
               </form>
                 <div class="col-lg-12" id="result_data">
                     
                 </div>  
            </div> 
        </div>
    </div> 
</section>
@endsection
@push('scripts')
<script type="text/javascript">
  
</script> 
<script type="text/javascript" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js">
@endpush


