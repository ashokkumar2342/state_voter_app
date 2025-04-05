
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
            <ul class="row ulrow">
                <li class="col-md-4 col-sm-6 col-xs-12">
                    <div class="card">
                        <img src="{{ asset('images/voterhelp.png') }}" alt="voterhelp" style="width: 100%">
                        <div class="card_container">
                            <h4>
                                <b>Voter Helpline</b></h4>
                            <p>
                            <a class="btn btn-primary" href="{{ route('front.search.voter') }}">
                                    Click to Search</a></p>
                        </div>
                        <p style="height: 50px">
                            &nbsp;</p>
                    </div>
                </li>
                <li class="col-md-4 col-sm-6 col-xs-12">
                    <div class="card">
                        <img src="{{ asset('images/download_voter_list.png') }}" alt="download_voter_list" style="width: 100%">
                        <div class="card_container">
                            <h4>
                                <b>Voter List Download</b></h4>
                            <p>
                                <a class="btn btn-primary" href="{{ route('front.download.voter.list') }}">
                                    Click to Download</a></p>
                        </div>
                        <p style="height: 50px">
                            &nbsp;</p>
                    </div>
                </li>
                
                <li class="col-md-4 col-sm-6 col-xs-12">
                    <div class="card">
                        <img src="{{ asset('images/voter_login.png') }}" alt="voter_login" style="width: 100%">
                        <div class="card_container">
                            
                            <h4>
                                <b>Voter List</b></h4>
                            <p>
                                <a class="btn btn-primary" href="{{ route('admin.login') }}">Click to Login</a></p>
                        </div>
                        <p style="height: 50px">
                            &nbsp;</p>
                    </div>
                </li>
                
                
            </ul>
            <br />
            <!--FOOTER AREA-->
            {{-- <div class="row">
                <footer id="footer-btm-area">
                
                            <div class="footer-copyright text-center">
                                <p>© 2022 <a href="#">Voter List Management Systems.</a> All Right Reserved to National Informatics Centre, Haryana.</p>
                            </div>
                
            </footer>
            </div> --}}
        </div>
    </div>

<!-- /.login-box -->

<!-- jQuery --> 
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
