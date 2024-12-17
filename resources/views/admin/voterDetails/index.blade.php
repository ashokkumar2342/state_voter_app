@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Add New Voter</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <button type="button" class="hidden" hidden="hidden" id="btn_refresh" onclick="window.location.reload();">refresh</button>
                </ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body"> 
                <form action="{{ route('admin.voter.details.store') }}" method="post" class="add_form" button-click="btn_refresh">
                    {{ csrf_field() }} 
                    <div class="row">  
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">District</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock') }}','block_select_box');">
                                <option selected disabled>Select District</option>
                                @foreach ($rs_district as $rs_val)
                                    <option value="{{ Crypt::encrypt($rs_val->opt_id) }}">{{ $rs_val->opt_text }}</option>  
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">Block / MC's</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="block" class="form-control select2" id="block_select_box" onchange="callAjax(this,'{{ route('admin.Master.BlockWiseVillage') }}'+'?id='+this.value+'&district_id='+$('#district_select_box').val(),'village_select_box')">
                                <option selected disabled>Select Block / MC's</option> 
                            </select>
                        </div> 
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">Panchayat MC's</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="village" class="form-control select2" id="village_select_box" onchange="callAjax(this,'{{ route('admin.voter.VillageWiseWard') }}','ward_no_select_box');callAjax(this,'{{ route('admin.voter.VillageWiseAcParts') }}'+'?village_id='+this.value,'assembly_select_box')">
                                <option selected disabled>Select Panchayat MC's</option> 
                            </select>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">Assembly-Part</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="ac_part_id" class="form-control select2" id="assembly_select_box">
                                <option selected disabled>Select Assembly-Part</option>
                                 
                            </select>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">Ward No.</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="ward_no" class="form-control select2" id="ward_no_select_box" onchange="callAjax(this,'{{ route('admin.Master.WardWiseBooth') }}'+'?ward_id='+this.value+'&village_id='+$('#village_select_box').val(),'booth_select_box')">
                                <option selected disabled>Select Ward No.</option> 
                            </select>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">Booth No.</label>
                            <span class="fa fa-asterisk"></span>                            
                            <select name="booth_no" class="form-control select2" id="booth_select_box">
                                <option selected disabled>Select Booth No.</option> 
                            </select>
                        </div> 
                        <div class="col-lg-6 form-group">
                            <label for="exampleInputEmail1">Sr. No. in Part</label> 
                            <span class="fa fa-asterisk"></span>
                            <input type="text" name="srno_part" id="srno_part" class="form-control" maxlength="5" onkeypress='return event.charCode >= 48 && event.charCode <= 57' required>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label for="exampleInputEmail1">Voter/Epic No.</label>
                            <span class="fa fa-asterisk"></span>
                            <input type="text" name="voter_id_no" id="voter_id_no" class="form-control" maxlength="20" required>
                        </div> 
                        <div class="col-lg-6 form-group">
                            <label for="exampleInputEmail1">Name (English)</label>
                            <span class="fa fa-asterisk"></span>
                            <input type="text" name="name_english" id="name_english" class="form-control" maxlength="50" required onkeyup="check_duplicate_rec();">
                        </div>
                        <div class="col-lg-6 form-group">
                            <label for="exampleInputEmail1">Name (Hindi)</label>
                            <span class="fa fa-asterisk"></span>
                            <input type="text" name="name_local_language" id="name_local_language"  class="form-control" maxlength="50" required>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">Relation</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="relation" id="relation" class="form-control select2">
                                <option selected disabled>Select Relation</option>
                                @foreach ($Relations as $Relation)
                                <option value="{{ Crypt::encrypt($Relation->id) }}">{{ $Relation->relation_e }}-{{ $Relation->relation_l }}</option> 
                                @endforeach 
                            </select>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">F/H Name (English)</label>
                            <span class="fa fa-asterisk"></span>
                            <input type="text" name="f_h_name_english" id="f_h_name_english" class="form-control" maxlength="50" required onkeyup="check_duplicate_rec();">
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">F/H Name (Hindi)</label>
                            <span class="fa fa-asterisk"></span>
                            <input type="text" name="f_h_name_local_language" id="f_h_name_local_language" class="form-control" maxlength="50" required>
                        </div> 
                        <div class="col-lg-6 form-group">
                            <label for="exampleInputEmail1">House No.(English)</label>
                            <span class="fa fa-asterisk"></span>
                            <input type="text" name="house_no_english" id="house_no_english" class="form-control" maxlength="20" required>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label for="exampleInputEmail1">House No.(Hindi)</label>
                            <span class="fa fa-asterisk"></span>
                            <input type="text" name="house_no_local_language" id="house_no_local_language" class="form-control" maxlength="20" required>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">Gender</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="gender" class="form-control select2" id="gender">
                                <option selected disabled>Select Gender</option>
                                @foreach ($genders as $gender)
                                <option value="{{ Crypt::encrypt($gender->id) }}">{{ $gender->genders }}-{{ $gender->genders_l }}</option>  
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">Date of Birth</label>
                            <span class="fa fa-asterisk"></span>
                            <input type="text" name="date_of_birth" id="date_of_birth" class="form-control" placeholder="DD-MM-YYYY" maxlength="10" minlength="10" required onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || (event.charCode == 45) || (event.charCode == 47)' onkeyup="Age_Count(this.value);check_duplicate_rec();"> 

                            {{-- <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" required onchange="callAjax(this,'{{ route('admin.voter.calculateAge') }}','age_value_div')"> --}}
                        </div>
                        <div class="col-lg-4 form-group" id="age_value_div">
                            <label for="exampleInputEmail1">Age</label>
                            <span class="fa fa-asterisk"></span>
                            <input type="text" name="age" id="age" class="form-control" maxlength="3" onkeypress='return event.charCode >= 48 && event.charCode <= 57' required>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">Aadhaar No.</label> 
                            <input type="text" name="Aadhaar_no" id="Aadhaar_no" class="form-control" maxlength="12" minlength="12" onkeypress='return event.charCode >= 48 && event.charCode <= 57' >
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">Mobile No.</label> 
                            <input type="text" name="mobile_no" id="mobile_no" class="form-control" maxlength="10" minlength="10" onkeypress='return event.charCode >= 48 && event.charCode <= 57' >
                        </div>
                        <div class="col-lg-4 form-group">
                            <label>Image (Only:JPG/JPEG/PNG) (Size:20KB)</label>
                            <span class="fa fa-asterisk"></span>
                            <input type="file" name="image" id="image" class="form-control" required accept="image/jpg, image/jpeg, image/png"> 
                        </div>
                    </div>
                        <div id="checkDuplicateRecord">

                        </div>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                    </div>
                 </form>
             </div>
         </div>
         <div class="card card-info"> 
            <div class="card-body">
                <div class="row" id="voter_list_table"></div>
            </div>
        </div>
     </div>
</section>
@endsection
@push('scripts')
<script>
    $("#name_local_language").keyup(function(event) {
        // alert(event.which);
        if(event.which == 113) {
            if ($("#name_english").val().length == 0) {
               alert('Please Enter Name (English)');
            }else{
               callPopupLarge(this,'{{ route('admin.voter.echeckdictionaryFName', 1) }}'+'?name_english='+$('#name_english').val());   
            }
        }
    });

    $("#f_h_name_local_language").keyup(function(event) {
        if(event.which == 113) { //=+
            if ($("#f_h_name_english").val().length == 0) {
               alert('Please Enter F/H Name (English)');
            }else{
               callPopupLarge(this,'{{ route('admin.voter.echeckdictionaryFName', 2) }}'+'?name_english='+$('#f_h_name_english').val());
            }
        }
    });

    $("#house_no_local_language").keyup(function(event) {
        if(event.which == 113) { //=+
            if ($("#house_no_english").val().length == 0) {
               alert('Please Enter House No. (English)');
            }else{
               callPopupLarge(this,'{{ route('admin.voter.echeckdictionaryFName', 3) }}'+'?name_english='+$('#house_no_english').val());
            }
        }
    });
</script>
<script>
    function EmpNameFill(val, con_type) {
        if (con_type == 1) {
            $("#name_local_language").val(val);
        }
        if (con_type == 2) {
            $("#f_h_name_local_language").val(val);
        }
        if (con_type == 3) {
            $("#house_no_local_language").val(val);
        }
        $("#btn_close").click();
        {{-- callAjax(this,'{{ route('admin.Master.employeeDictionaryApplyUpdate') }}'+'?dic_id='+id); --}}
    }
</script>
<script>
    function Age_Count(dob) {
        if (dob.length == 10) {            
            if(isValidDate(dob)){
                const [day, month, year] = dob.split('-').map(Number);

                // Handle the year for two-digit format (e.g., 99 -> 1999 or 2000)
                const fullYear = year < 100 ? (year > 50 ? 1900 + year : 2000 + year) : year;

                // Create a Date object for the DOB
                const dobDate = new Date(fullYear, month - 1, day); // Months are zero-based in JavaScript

                // Get the current date
                const today = new Date();

                // Calculate the difference in years
                let age = today.getFullYear() - dobDate.getFullYear();

                // Adjust the age if the birthday hasn't occurred yet this year
                const hasHadBirthdayThisYear =
                    today.getMonth() > dobDate.getMonth() ||
                    (today.getMonth() === dobDate.getMonth() && today.getDate() >= dobDate.getDate());
                if (!hasHadBirthdayThisYear) {
                    age--;
                }

                // return age;
                $("#age").val(age);
            }else{
                document.getElementById("age").value = 0;
            }   
        }
    }


    function isValidDate(dateString) {
        // Regular expression to match the format dd-mm-yyyy
        const regex = /^(\d{2})-(\d{2})-(\d{4})$/;
        const match = dateString.match(regex);

        if (!match) {
            return false; // Invalid format
        }

        const day = parseInt(match[1], 10);
        const month = parseInt(match[2], 10);
        const year = parseInt(match[3], 10);

        // Check if month is valid
        if (month < 1 || month > 12) {
            return false;
        }

        // Check if day is valid based on month and year
        const daysInMonth = new Date(year, month, 0).getDate();
        return day > 0 && day <= daysInMonth;
    }
</script>
<script>
    function check_duplicate_rec() {
        var name_e = $("#name_english").val();
        var f_name_e = $("#f_h_name_english").val();
        var dob = $("#date_of_birth").val();
        if ((name_e.length > 0) && (f_name_e.length > 0) && (dob.length == 10)) {
            callAjax(this,'{{ route('admin.voter.check.duplicate.record') }}'+'?name_english='+name_e+'&f_h_name_english='+f_name_e+'&date_of_birth='+dob,'checkDuplicateRecord');
        }
        
    }
</script>

@endpush

