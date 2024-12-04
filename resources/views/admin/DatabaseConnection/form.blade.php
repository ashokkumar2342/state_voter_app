@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Database Connection Setting</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div> 
         <div class="card">
         <div class="card-body login-card-body"> 
         <form action="{{ route('admin.database.conection.store') }}" method="post" class="add_form" no-reset="true">
         {{ csrf_field() }}
         <div class="col-md-12" data-select2-id="29">
         <div class="form-group">
         <label>IP(URL)</label>
         <input type="text" name="ip" class="form-control" placeholder="Enter IP(URL)" value="{{ $serverName }}">
         </div> 
         </div> 
         <div class="col-md-12" data-select2-id="29">
         <div class="form-group">
         <label>Database Name</label>
         <input type="text" name="database" class="form-control" placeholder="Enter Database Name"  value="{{ $database }}">
         </div> 
         </div>
         <div class="col-md-12" data-select2-id="29">
         <div class="form-group">
         <label>User Name</label>
         <input type="text" name="user_name" class="form-control" autocomplete="false" value="{{ $username }}">
         </div> 
         </div> 
         <div class="col-md-12" data-select2-id="29">
         <div class="form-group">
         <label>Password</label>
         <input type="text" name="password" class="form-control" value="{{ $passward }}">
         </div> 
         </div>
         <div class="col-md-12" data-select2-id="29">
         <div class="form-group">
          
         <input type="submit" class="form-control btn-primary" value="Set Connection">
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

