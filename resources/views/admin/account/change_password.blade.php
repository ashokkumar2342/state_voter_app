@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Change Password</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body">
                <form  action="{{ route('admin.account.change.password.store') }}"   class="add_form" method="post" autocomplete="off" >
                    {{ csrf_field()}}
                    <div class="form-body overflow-hide">
                        <div class="form-group">
                            <label class="control-label mb-10">Old Password</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="icon-lock"></i></div>
                                <input type="password" class="form-control" name="oldpassword" id="oldpassword" placeholder="Enter Old Password" required="">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="control-label mb-10" for="exampleInputpwd_01">New Password</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="icon-lock"></i></div>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Enter New Password"  title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="control-label mb-10" for="exampleInputpwd_01">Confirm password</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="icon-lock"></i></div>
                                <input type="password" name="passwordconfirmation" class="form-control" id="passwordconfirmation" placeholder="Enter Confirm Password"  title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" oninput="check(this)" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions mt-10">            
                        <button type="submit" class="btn btn-success mr-10 mb-30">Update Password</button>
                    </div>              
                </form>
                
            </div> 
        </div><!-- /.container-fluid -->
    </section>
    @endsection 

