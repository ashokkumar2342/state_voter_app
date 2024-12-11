@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Processing Status</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <button type="button" style=" margin-bottom: 10px;background-color: #c3cad1" onclick="document.location.reload(true)" class="btn btn-block pull-right"><i class="fa fa-refresh"></i> <b>Refresh</b></button> 
                </ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body"> 
                <div class="row">
                    <div class="col-lg-12">
                        <fieldset class="fieldset_border">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="ajax_data_table">
                                    <thead class="thead-dark">
                                        <tr>
                                            
                                            <th>Sr. No.</th>
                                            <th>District</th>
                                            <th>Block</th>
                                            <th>Village</th>
                                            <th>Ward</th> 
                                            <th>Booth</th> 
                                            <th>Report Type</th>
                                            <th>Submit Time</th>
                                            <th>Expected Time to Process</th> 
                                        </tr>
                                    </thead>
                                    @php
                                        $srno = 1;
                                    @endphp
                                    <tbody>
                                        @foreach ($voterlistprocesseds as $voterlistprocessed)
                                            <tr> 
                                                <td>{{ $srno++}}</td>
                                                <td>{{ $voterlistprocessed->d_name}}</td>
                                                <td>{{ $voterlistprocessed->b_code}} - {{ $voterlistprocessed->b_name}}</td>
                                                <td>{{ $voterlistprocessed->name_e}}</td>
                                                <td>{{ $voterlistprocessed->ward_no}}</td> 
                                                <td>{{ $voterlistprocessed->booth_no}}</td> 
                                                <td>{{ $voterlistprocessed->report_type}}</td>
                                                <td>{{ $voterlistprocessed->submit_time}}</td>
                                                <td>{{ $voterlistprocessed->expected_time_start}}</td>
                                                @if ($voterlistprocessed->status==2)
                                                    <td>Processing</td>
                                                    <td>Processing</td>
                                                    <td>Processing</td>
                                                @else
                                                    <td>Pending</td>
                                                    <td>Pending</td>
                                                    <td>Pending</td>
                                                @endif
                                            </tr>                                     
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection