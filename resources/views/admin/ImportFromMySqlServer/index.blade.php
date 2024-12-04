@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Import From MySQL Server</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div> 
         <div class="card">
         <div class="card-body login-card-body">
         <form action="{{ route('admin.mysql.server.DataTransfer') }}" method="post" class="add_form" no-reset="true">
         {{csrf_field()}} 
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-gray">
                        <div class="card-header">
                        <h3 class="card-title">From</h3>
                        </div>
                        <div class="card-body"> 
                        <div class="form-group">
                        <label>From District</label>
                        <select class="form-control" name="from_district" id="from_district_select_box" select-triger="from_block_select_box" onchange="callAjax(this,'{{ route('admin.mysql.server.districtWiseBlock') }}','from_block_select_box')">
                        <option selected disabled>Select From District</option>
                        @foreach ($from_districts as $from_district)
                        <option value="{{$from_district->id}}">{{$from_district->code}}-{{$from_district->name_e}}</option>
                        @endforeach 
                        </select> 
                        </div>
                        <div class="form-group">
                        <label for="exampleInputEmail1">From Block</label> 
                        <select name="from_block" class="form-control select2" id="from_block_select_box" onchange="callAjax(this,'{{ route('admin.mysql.server.BlockWiseWillage') }}'+'?id='+this.value+'&district_id='+$('#from_district_select_box').val(),'from_village_select_box')">
                            <option selected disabled>Select From Block</option> 
                        </select>
                        </div>
                        <div class="form-group">
                        <label for="exampleInputEmail1">From Panchayat</label> 
                        <select name="from_panchayat" class="form-control select2" id="from_village_select_box" select2="true">
                            <option selected disabled>Select From Panchayat</option>
                            
                        </select>
                        </div> 
                        </div> 
                    </div> 
                </div>
                <div class="col-md-6">
                    <div class="card card-gray">
                        <div class="card-header">
                        <h3 class="card-title">To</h3>
                        </div>
                        <div class="card-body"> 
                        <div class="form-group">
                        <label>To District</label>
                        <select class="form-control" name="to_district" id="to_district_select_box" select-triger="to_block_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock') }}','to_block_select_box')">
                        <option selected disabled>Select TO District</option>
                        @foreach ($to_districts as $to_districts)
                        <option value="{{$to_districts->code}}">{{$to_districts->code}}-{{$to_districts->name_e}}</option>
                        @endforeach 
                        </select> 
                        </div>
                        <div class="form-group">
                        <label for="exampleInputEmail1">To Block</label> 
                        <select name="to_block" class="form-control select2" id="to_block_select_box" onchange="callAjax(this,'{{ route('admin.Master.BlockWiseVillage') }}'+'?id='+this.value+'&district_id='+$('#to_district_select_box').val(),'to_village_select_box')">
                            <option selected disabled>Select To Block</option> 
                        </select>
                        </div>
                        <div class="form-group">
                        <label for="exampleInputEmail1">To Panchayat</label> 
                        <select name="to_panchyat" class="form-control select2" id="to_village_select_box" select2="true">
                            <option selected disabled>Select To Panchayat</option>
                            
                        </select>
                        </div> 
                        </div> 
                    </div> 
                </div>
            </div>
            <div class="col-md-12 text-center">
            <div class="form-group"> 
            <input type="submit" class="btn-primary btn" value="Submit">
            </div> 
            </div>
        </form>
        </div>
    </div>
</div>
</section>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
    
    $("#btn_click_by_delete_form").click();
        
    
    });

</script>

