@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Assembly</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                
                </ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card card-primary"> 
                            <form action="{{ route('admin.Master.Assembly.store') }}" method="post" class="add_form" select-triger="district_select_box">
                                {{ csrf_field() }}
                                <div class="card-body"> 
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">District</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="district" id="district_select_box" class="form-control" data-table="district_table" onchange="callAjax(this,'{{ route('admin.Master.AssemblyTable') }}','assembly_table')"> 
                                            @foreach ($Districts as $District)
                                            <option value="{{ $District->id }}">{{ $District->code }}--{{ $District->name_e }}</option>  
                                            @endforeach
                                        </select>
                                    </div> 
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Assembly Code</label>
                                        <span class="fa fa-asterisk"></span>
                                        <input type="text" name="code" class="form-control" placeholder="Enter Code" maxlength="5" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Assembly Name (English)</label>
                                        <span class="fa fa-asterisk"></span>
                                        <input type="text" name="name_english" class="form-control" placeholder="Enter Name (In English)" maxlength="50" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Assembly Name (Hindi)</label>
                                        <span class="fa fa-asterisk"></span>
                                        <input type="text" name="name_local_language" class="form-control" placeholder="Enter Name (In Hindi)" maxlength="100" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Parts To Be Created </label>
                                        <span class="fa fa-asterisk"></span>
                                        <input type="text" name="part_no" class="form-control" placeholder="Total Parts" maxlength="3" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                    </div> 
                                </div> 
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div> 
                    </div>
                    <div class="col-lg-8">
                        <div class="card card-primary table-responsive" id="assembly_table"> 
                             <table id="district_table" class="table table-striped table-hover control-label">
                                 <thead>
                                     <tr>
                                          
                                         <th>Code</th>
                                         <th>Name (English)</th>
                                         <th>Name (Hindi)</th>
                                         <th>Total Part</th>
                                         <th>Action</th>
                                          
                                     </tr>
                                 </thead>
                                 <tbody id="assembly_table">
                                    
                                 </tbody>
                             </table>
                        </div> 
                    </div> 
                </div>
            </div> 
        </div> 
    </section>
    @endsection
    @push('scripts')
    <script>
        $('#district_select_box').trigger('change');
        $('#district_table').DataTable();
    </script>
    @endpush

