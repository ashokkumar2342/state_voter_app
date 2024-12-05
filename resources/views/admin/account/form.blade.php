@php
    Session::put('CryptoRandom',App\Helper\MyFuncs::generateId());
    Session::put('CryptoRandomInfo',App\Helper\MyFuncs::generateRandomIV());
@endphp

@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Add New User</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"></ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body">
                <form action="{{ route('admin.account.post') }}" method="post" class="add_form" autocomplete="off" onsubmit="return hashPasswordEncryption();">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Name</label>
                            <span class="fa fa-asterisk"></span>
                            <input Name="first_name" class="form-control"  placeholder="Enter First Name" required="" maxlength="50">
                        </div>                                
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Role</label>
                            <span class="fa fa-asterisk"></span>
                            <select class="form-control select2" name="role_id">
                                @foreach($roles as $role)
                                    <option value="{{ Crypt::encrypt($role->id) }}">{{ $role->name }}</option>
                                @endforeach 
                            </select>
                        </div>                               
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>Email ID</label>
                            <span class="fa fa-asterisk"></span> 
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Enter Email" maxlength="50">
                            </div> 
                        </div>
                    </div> 
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>Mobile No.</label>
                            <span class="fa fa-asterisk"></span> 
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                <input type="text" Name="mobile" class="form-control" maxlength="10" onkeypress='return event.charCode >= 48 && event.charCode <= 57' placeholder="Enter Mobile No.">
                            </div> 
                        </div>
                    </div> 
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>Password (Min. 8 Max. 15 Characters )</label>
                            <span class="fa fa-asterisk"></span> 
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                            </div> 
                        </div>
                    </div> 
                    
                     
                </div>   
                <div class="box-footer text-center" style="margin-top: 30px">
                    <button type="submit" class="btn btn-primary form-control">Submit</button>
                </div> 
              </form>  <!-- /.card-body -->
            </div> 
        </div><!-- /.container-fluid -->
    </div>
</section>
@endsection 

@push('scripts')
<script src={!! asset('admin_asset/dist/js/crypto-js.min.js?ver=') !!}{{date('Y-m-d')}}></script>
<script>
    function hashPasswordEncryption(){
        var password = jQuery("#password").val();
        
        var Cryptoksduid = '<?php echo Session::get('CryptoRandom');?>';
        var Cryptoikeywords = '<?php echo Session::get('CryptoRandomInfo');?>';
        var Cryptokfydsdyg = CryptoJS.enc.Utf8.parse(Cryptoksduid);
        
        var encrypted = CryptoJS.DES.encrypt(password,
        Cryptokfydsdyg, {
            mode: CryptoJS.mode.CBC,
            padding: CryptoJS.pad.Pkcs7,
            iv: CryptoJS.enc.Utf8.parse(Cryptoikeywords)
        });

        var hexstr = encrypted.ciphertext.toString();
        jQuery("#password").val(hexstr);
    }
</script>

@endpush 
