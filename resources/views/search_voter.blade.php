
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
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('admin_asset/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}"> 
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('admin_asset/dist/css/AdminLTE.min.css')}}"> 
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
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



/*.card__inner
{
    background-color: #468AC6;
    color: #fff;
    position: absolute;
    top: 0px;
    bottom: 0px;
    left: 0px;
    right: 0px;
    z-index: 1;
    opacity: 0;
    padding: 2rem 1.3rem 2rem 2rem;
    transition: all 0.4s ease 0s;
}

.card:hover .card__inner
{
    opacity: 1;
}

.card__inner h2
{
    margin-top: 1rem;
}

.card__inner p
{
    height: 87%;
    padding-right: 1rem;
    font-weight: 200;
    line-height: 2.5rem;
    margin-top: 1.5rem;
}*/
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

</style>
<body>
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
                
                <form {{-- action="{{ route('admin.search.voter.folter',2) }}" --}} method="post" class="add_form" data-table="voter_datatable" success-content-id="voter_table" no-reset="true">
                    {{ csrf_field() }}
                    <div class="row">  
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">District</label>
                            
                            <select name="district" class="form-control" id="district_select_box" {{-- onchange="callAjax(this,'{{ route('admin.search.dis.block') }}','block_select_box')" --}}>
                                <option selected disabled>Select District</option>
                                {{-- @foreach ($Districts as $District)
                                <option value="{{ $District->id }}">{{ $District->code }}--{{ $District->name_e }}</option>  
                                @endforeach --}}
                            </select>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">Block MCS</label>
                            
                            <select name="block" class="form-control" id="block_select_box" {{-- onchange="callAjax(this,'{{ route('admin.search.block.village') }}'+'?id='+this.value+'&district_id='+$('#district_select_box').val(),'village_select_box')" --}}>
                                <option selected disabled>Select Block MCS</option> 
                            </select>
                        </div> 
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">Village</label>
                            
                            <select name="village" class="form-control" id="village_select_box">
                                <option selected disabled>Select Village</option> 
                            </select>
                        </div> 
                        <div class="col-lg-3 form-group">
                            <label for="exampleInputEmail1">V Name</label>
                            <input type="text" name="v_name" class="form-control"> 
                        </div>
                        <div class="col-lg-3 form-group">
                            <label for="exampleInputEmail1">F/H Name</label>
                            <input type="text" name="father_name" class="form-control"> 
                        </div>
                        <div class="col-lg-3 form-group">
                            <label for="exampleInputEmail1">Age</label>
                            <select name="age" class="form-control">
                                <option value="0">All</option>
                                <option value="18 and 25">18 To 25</option>
                                <option value="25 and 35">25 To 35</option>
                                <option value="35 and 45">35 To 45</option>
                                <option value="45 and 55">45 To 55</option>
                                <option value="55 and 100">55 To 100</option> 
                                <option value="100 and 150">Above 100</option> 
                            </select> 
                        </div>
                        <div class="col-lg-3 form-group">
                            <label for="exampleInputEmail1">Mobile No.</label>
                            <input type="text" name="mobile_no" class="form-control"> 
                        </div>
                        <div class="col-lg-12 form-group">
                            <input type="submit" id="btn_show" value="Search" class="form-control btn btn-success">
                        </div>
                    </div>
                </form>
                <div id="voter_table">

                </div> 
        </div>
    </div>

<!-- /.login-box -->

<!-- jQuery -->
<script src={!! asset('admin_asset/dist/js/validation/common.js?ver=1') !!}></script>
<script src={!! asset('admin_asset/dist/js/customscript.js?ver=1') !!}></script> 
<script src="{{ asset('admin_asset/plugins/jquery/jquery.min.js') }}"></script>

<!-- Bootstrap 4 -->
<script src="{{ asset('admin_asset/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- AdminLTE App -->
<script src="{{ asset('admin_asset/dist/js/adminlte.min.js') }}"></script>

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
