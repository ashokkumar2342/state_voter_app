@extends('admin.layout.base')
@section('body')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a>Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box" style="background: rgb(51, 102, 204);color: #fff;">
              <div class="inner">
                <h3>{{ $District }}</h3>
                <p>District</p>
              </div>
              <div class="icon">
                <i class="fa fa-university"></i>
              </div>
              <a class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{ $block }}</h3>

                <p>Block/MC's</p>
              </div>
              <div class="icon">
                <i class="fa fa-university"></i>
              </div>
              <a class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box" style="background: #ff9900;">
              <div class="inner">
                <h3>{{ $village }}</h3>
                <p>Panchayat/MC's</p>
              </div>
              <div class="icon">
                <i class="fa fa-university"></i>
              </div>
              <a class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{ $wardVillage }}</h3>

                <p>Total Ward</p>
              </div>
              <div class="icon">
                <i class="fa fa-university"></i>
              </div>
              <a class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          </div>
        </div>
    </section>
@endsection 

