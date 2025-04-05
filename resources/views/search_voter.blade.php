
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Voter List Management | Log in</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{ asset('admin_asset/plugins/fontawesome-free/css/all.min.css')}}">
  <link rel="stylesheet" href="{{ asset('admin_asset/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{ asset('admin_asset/dist/css/AdminLTE.min.css')}}">
  <link rel="stylesheet" href="{{ asset('admin_asset/plugins/summernote/summernote-bs4.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('admin_asset/dist/css/toastr.min.css')}}">
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
<body id="body_id">
    <div class="container-fluid">
        <div class="header-top">
            <div class="row">
                <div class="site-logo img-responsive">
                    <img src="{{ asset('images/voter_managenet_banner.png') }}" alt="voter_managenet_banner"/>
                </div>
            </div>
            <br/>
            <div class="card card-info"> 
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-12">
                            <div class="card card-primary card-outline card-tabs">
                                <div class="card-header p-0 pt-1 border-bottom-0">
                                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">ईपीआईसी द्वारा खोजें / Search by EPIC</a>
                                        </li>
                                        {{-- <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">विवरण द्वारा खोजें/ Search by Details</a>
                                        </li> --}}
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-three-tabContent">
                                        <div class="tab-pane fade active show" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                                            <form action="{{ route('admin.search.voter.filter',1) }}" method="post" class="add_form" success-content-id="voter_table" data-table="result_table" no-reset="true">
                                            {{ csrf_field() }}
                                                <div class="row">
                                                    <div class="col-lg-6 form-group">
                                                        <label for="exampleInputEmail1">ईपीआईसी संख्या/EPIC Number</label>
                                                        <span class="fa fa-asterisk text-danger"></span>
                                                        <input type="text" name="voter_card_no" class="form-control" required maxlength="20"> 
                                                    </div>
                                                    <div class="col-lg-6 form-group" style="margin-top: 28px;">
                                                        <input type="submit" class="btn btn-primary form-control" value="SEARCH"> 
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
                                            <form action="{{ route('admin.search.voter.filter',2) }}" method="post" class="add_form" success-content-id="voter_table" data-table="result_table" no-reset="true">
                                            {{ csrf_field() }}
                                                <div class="row">
                                                    <div class="col-lg-6 form-group">
                                                        <label for="exampleInputEmail1">District</label>
                                                        <span class="fa fa-asterisk text-danger"></span>
                                                        <select name="district" class="form-control" id="district_select_box" onchange="callAjax(this,'{{ route('front.DistrictWiseMC') }}','village_select_box')" required>
                                                            <option selected disabled>Select District</option>
                                                            @foreach ($rs_district as $District)
                                                                <option value="{{ Crypt::encrypt($District->opt_id) }}">{{ $District->opt_text }}</option>  
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-6 form-group">
                                                        <label for="exampleInputEmail1">MC</label>
                                                        <span class="fa fa-asterisk text-danger"></span>
                                                        <select name="village" class="form-control" id="village_select_box" required>
                                                            <option selected disabled>Select MC</option> 
                                                        </select>
                                                    </div> 
                                                    <div class="col-lg-4 form-group">
                                                        <label for="exampleInputEmail1">Name (Min. 2 Char)</label>
                                                        <span class="fa fa-asterisk text-danger"></span>
                                                        <input type="text" name="v_name" class="form-control" minlength="2" maxlength="50" required> 
                                                    </div>
                                                    <div class="col-lg-4 form-group">
                                                        <label for="exampleInputEmail1">Father's/Husband's (Min. 2 Char)</label>
                                                        <span class="fa fa-asterisk text-danger"></span>
                                                        <input type="text" name="father_name" class="form-control" minlength="2" maxlength="50" required> 
                                                    </div>
                                                    <div class="col-lg-4 form-group">
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
                                                    <div class="col-lg-12 form-group" style="margin-top: 28px;">
                                                        <input type="submit" class="btn btn-primary form-control" value="SEARCH"> 
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-info"> 
                <div class="card-body">
                    <div class="row" id="voter_table">
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="{{ asset('admin_asset/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('admin_asset/plugins/jQuery/jquery.min.js') }}"></script>
<script src="{{ asset('admin_asset/dist/js/toastr.min.js') }}"></script>
<script src={!! asset('admin_asset/dist/js/validation/common.js?ver=1') !!}></script>
<script src={!! asset('admin_asset/dist/js/customscript.js?ver=1') !!}></script> 
<script src="{{ asset('admin_asset/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin_asset/plugins/summernote/summernote-bs4.min.js') }}"></script>
@include('admin.include.message')
</body>
</html>

