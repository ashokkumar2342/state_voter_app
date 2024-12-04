@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Change Voters Ward Excel</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                            <form action="{{ route('admin.Master.change.voter.with.ward.excel.store') }}" method="post" class="add_form" success-content-id="result_data_div">
                                {{ csrf_field() }}
                                <div class="card-body">
                                <div class="row"> 
                                <div class="col-lg-12 form-group">
                                    <label>Excel File</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="excel_file" id="customFile" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div> 
                                </div>
                                  <div class="col-lg-12 form-group">
                                    <input type="submit" class="btn btn-success form-control">
                                      
                                  </div>  
                                </div> 
                            </form>
                            <div class="row">
                                <div id="result_data_div">
                                    
                                </div>
                                
                            </div>
                    </div>
                     
                </div>
            </div> 
        </div> 
    </section>
    @endsection
<script type="text/javascript">
    function disablewardNo() {
    document.getElementById("ward_select_box").disabled = false;
}

</script>
   