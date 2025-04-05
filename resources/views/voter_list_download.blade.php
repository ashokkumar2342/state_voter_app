
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Voter List Management | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome --> 
  <link rel="stylesheet" href="{{ asset('admin_asset/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  {{-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> --}}
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('admin_asset/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}"> 
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('admin_asset/dist/css/AdminLTE.min.css')}}">
  <link rel="stylesheet" href="{{ asset('admin_asset/plugins/summernote/summernote-bs4.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('admin_asset/dist/css/toastr.min.css')}}">
  <link rel="stylesheet" href="{{ asset('admin_asset/plugins/select2/css/select2.min.css')}}">

  <!-- Google Font: Source Sans Pro -->
  {{-- <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> --}}
</head>
<style>
.card
{
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
    transition: 0.3s;
    width: 100%;
    border-radius: 5px;
    background-color: #D9E6ED;
    margin-bottom: 20px;
}

.card:hover
{
    box-shadow: 0 8px 16px 0 rgba(0.9,0.9,0.9,0.9);
}

.card img
{
    padding: 20px;
    border-radius: 5px 5px 0 0;
}




.card_container
{
    padding: 2px 16px 8px 16px;
}

.card2
{
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
    transition: 0.3s;
    border-radius: 5px;
    background-color: #D9E6ED;
    margin-bottom: 20px;
}


.btn
{
    border: none;
    color: white;
    padding: 7px 10px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 25px;
}
.btn-primary
{
    background-color: #4CAF50; /*background-color: #408DD3;*/
}
.btn a
{
    color: White;
}

.ulrow
{
    list-style: none;
    padding: 0px;
}
.ulrow li
{
    padding-bottom: 10px;
}



.main-box {
    height: 95vh;
}

/*---------------------------------
    Login Page CSS Start 
    
----------------------------*/

.site-logo img {

    width: 100%;
    /*margin-top: -20px;*/
    border-bottom: 5px groove #6495ED;
}


.footer-copyright.text-center p {
    margin-bottom: 0;
}
/*----------------------------
    FOOTER BOTTOM AREA
----------------------*/

#footer-btm-area {
    background: #000000;
    color: #fff;
    border-top: 6px groove #6495ED;
}

.footer-copyright.text-center {
    padding: 10px 0 10px;
}

    .footer-copyright.text-center p {
        margin-bottom: 0;
    }

.footer-copyright {
    padding: 10px 0;
}

    .footer-copyright a {
        color: #eee;
    }

    .footer-copyright p a:hover {
        color: #fff;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
    padding-left: 0;
    height: auto;
    margin-top: -8px;

</style>
<body id="body_id">
    <div class="container-fluid">
        <div class="header-top">
            <div class="row">
                <div class="site-logo img-responsive">
                    <img src="{{ asset('images/voter_managenet_banner.png') }}" alt="voter_managenet_banner"/>
                </div>
            </div>
            <br />
              <div class="card card-info"> 
            <div class="card-body">
            <form action="{{ route('front.tableShow') }}" class="add_form" method="post" success-content-id="table_show" no-reset="true" button-click="refresh" reset-input-text="captcha">
            {{csrf_field()}}  
                <div class="row">
                <div class="col-lg-3 form-group">
                    <label for="exampleInputEmail1">States</label>
                    <span class="fa fa-asterisk text-danger"></span>
                    <select name="states" id="state_id" class="form-control select2" onchange="callAjax(this,'{{ route('front.stateWiseDistrict') }}','district_select_box')">
                        <option selected disabled>Select States</option>
                        @foreach ($States as $State)
                        <option value="{{ Crypt::encrypt($State->id) }}">{{ $State->code }}--{{ $State->name_e }}</option>  
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 form-group">
                    <label for="exampleInputEmail1">District</label>
                    <span class="fa fa-asterisk text-danger"></span>
                    <select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('front.DistrictWiseBlock') }}','block_select_box')">
                        <option selected disabled value="{{ Crypt::encrypt(0) }}">Select District</option>
                    </select>
                </div>
                <div class="col-lg-3 form-group">
                    <label for="exampleInputEmail1">Block / MC's</label>
                    <span class="fa fa-asterisk text-danger"></span>
                    <select name="block" class="form-control select2" id="block_select_box" onchange="callAjax(this,'{{ route('front.BlockWiseVoterListType') }}','voter_list_master_id')">
                        <option selected disabled value="{{ Crypt::encrypt(0) }}">Select Block MCS</option> 
                    </select>
                </div>
                <div class="col-lg-3 form-group">
                    <label for="exampleInputEmail1">Voter List</label>
                    <span class="fa fa-asterisk text-danger"></span>
                    <select name="voter_list_master_id" class="form-control select2" id="voter_list_master_id" {{-- onchange="callAjax(this,'{{ route('admin.voter.BlockWiseDownloadTable1') }}'+'?block_id='+$('#block_select_box').val()+'&state_id='+$('#state_id').val()+'&district_id='+$('#district_select_box').val()+'&voter_list_master_id='+$('#voter_list_master_id').val(),'download_table')" --}}>
                        <option selected disabled value="{{ Crypt::encrypt(0) }}">Select Voter List</option>
                    </select>
                </div>
                <div class="col-lg-3" style="margin-top: 10px;background-color: #fff">
                     <div class="captcha">
                      <span>{!! captcha_img('math') !!}</span>
                      <button type="button" class="btn btn-success" id="refresh"><i class="fas fa-1x fa-sync-alt" ></i></button>
                    </div>
                 </div>   
                  <div class="col-lg-3" style="margin-top: 30px">
                     <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha"> 
                     
                   </div>
                   <p class="text-danger">{{ $errors->first('captcha') }}</p>  
                
                <div class="col-lg-6">
                  <input type="submit" class="btn btn-success form-control" value="Show" style="margin-top: 30px">  
                </div>
            </div>
            <div style="margin-top: 10px" id="table_show">
                 
            </div> 
          </form>
        </div>
    </div>

<!-- /.login-box -->

<!-- jQuery -->
 
<script src="{{ asset('admin_asset/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('admin_asset/dist/js/adminlte.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('admin_asset/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin_asset/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('admin_asset/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('admin_asset/dist/js/adminlte.min.js') }}"></script>
<script src={!! asset('admin_asset/dist/js/validation/common.js?ver=1') !!}></script>
<script src={!! asset('admin_asset/dist/js/customscript.js?ver=1') !!}></script>
<script src="{{ asset('admin_asset/dist/js/toastr.min.js') }}"></script>

</body>
</html>
<script type="text/javascript">
$('#refresh').click(function(){
  $.ajax({
     type:'GET',
     url:'{{ route('admin.refresh.captcha') }}',
     success:function(data){
        $(".captcha span").html(data);
     }
  });
});
</script>
<script>
  $(".select2").select2();
</script>
