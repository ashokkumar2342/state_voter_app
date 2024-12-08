@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Master Data Report</h3>
            </div>
        </div> 
    </div>
    <div class="card card-info">
        <div class="card-body">
            <form  class="add_form" method="post" action="{{ route('admin.report.show') }}" success-content-id="result_div_id" no-reset="true" data-table-new-without-pagination="example1"> 
            {{ csrf_field() }} 
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Report Type</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="report_type" class="form-control select2" select2="true" onchange="callAjax(this,'{{ route('admin.report.formControl.show') }}','form_controls')" required>
                                <option value="{{ Crypt::encrypt(0) }}" disabled selected>Select Report</option>
                                @foreach ($reportTypes as $reportType)
                                    <option value="{{ Crypt::encrypt($reportType->report_id) }}">{{ $reportType->name }}</option>
                                @endforeach
                            </select>
                        </div>                               
                    </div>  
                </div>
                <div class="row" id="form_controls"> 
                        
                </div>
            </form> 
        </div>
    </div>
    <div class="card card-info">
        <div class="card-body">
            <div class="row" id="result_div_id"> 

            </div>    
        </div>
    </div> 
</section>
@endsection
@push('scripts')
<script>
    $(function () {
        $("#example").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["excel", "colvis"]
        }).buttons().container().appendTo('#example_wrapper .col-md-6:eq(0)');
    });
</script>
@endpush
