@if (count($rs_record) == 0)
    <input type="hidden" name="rec_id" id="rec_id" required value="{{Crypt::encrypt(0)}}">    
@else
    <input type="hidden" name="rec_id" id="rec_id" required value="{{Crypt::encrypt(@$rs_record[0]->id)}}">
@endif

<div class="col-lg-4 form-group">
    <label for="exampleInputEmail1">Voter/Epic No.</label>
    <span class="fa fa-asterisk"></span>
    <input type="text" name="voter_id_no" id="voter_id_no" class="form-control" maxlength="20" required value="{{@$rs_record[0]->voter_card_no}}" onblur="check_duplicate_rec(1)">
</div> 
<div class="col-lg-4 form-group">
    <label for="exampleInputEmail1">Name (English)</label>
    <span class="fa fa-asterisk"></span>
    <input type="text" name="name_english" id="name_english" class="form-control" maxlength="50" required onblur="getTranslatedata()" value="{{@$rs_record[0]->name_e}}">
</div>
<div class="col-lg-4 form-group">
    <label for="exampleInputEmail1">Name (Hindi)</label>
    <span class="fa fa-asterisk"></span>
    <input type="text" name="name_local_language" id="name_local_language"  class="form-control" maxlength="50" required value="{{@$rs_record[0]->name_l}}">
</div>
<div class="col-lg-4 form-group">
    <label for="exampleInputEmail1">Relation</label>
    <span class="fa fa-asterisk"></span>
    <select name="relation" id="relation" class="form-control select2">
        <option selected disabled>Select Relation</option>
        @foreach ($Relations as $Relation)
        <option value="{{ Crypt::encrypt($Relation->id) }}"{{$Relation->id==@$rs_record[0]->relation?'selected':''}}>{{ $Relation->relation_e }}-{{ $Relation->relation_l }}</option> 
        @endforeach 
    </select>
</div>
<div class="col-lg-4 form-group">
    <label for="exampleInputEmail1">F/H Name (English)</label>
    <span class="fa fa-asterisk"></span>
    <input type="text" name="f_h_name_english" id="f_h_name_english" class="form-control" maxlength="50" required onblur="getTranslatedata2()" value="{{@$rs_record[0]->father_name_e}}">
</div>
<div class="col-lg-4 form-group">
    <label for="exampleInputEmail1">F/H Name (Hindi)</label>
    <span class="fa fa-asterisk"></span>
    <input type="text" name="f_h_name_local_language" id="f_h_name_local_language" class="form-control" maxlength="50" required value="{{@$rs_record[0]->father_name_l}}">
</div> 

<div class="col-lg-4 form-group">
    <label for="exampleInputEmail1">House No.(English)</label>
    <span class="fa fa-asterisk"></span>
    <input type="text" name="house_no_english" id="house_no_english" class="form-control" maxlength="20" required value="{{@$rs_record[0]->house_no_e}}" onblur="getTranslatedata3()">
</div>
<div class="col-lg-4 form-group">
    <label for="exampleInputEmail1">House No.(Hindi)</label>
    <span class="fa fa-asterisk"></span>
    <input type="text" name="house_no_local_language" id="house_no_local_language" class="form-control" maxlength="20" required value="{{@$rs_record[0]->house_no_l}}">
</div>
<div class="col-lg-4 form-group">
    <label for="exampleInputEmail1">Gender</label>
    <span class="fa fa-asterisk"></span>
    <select name="gender" class="form-control select2" id="gender">
        <option selected disabled>Select Gender</option>
        @foreach ($genders as $gender)
        <option value="{{ Crypt::encrypt($gender->id) }}"{{$gender->id==@$rs_record[0]->gender_id?'selected':''}}>{{ $gender->genders }}-{{ $gender->genders_l }}</option>  
        @endforeach
    </select>
</div>
<div class="col-lg-3 form-group">
    <label for="exampleInputEmail1">Date of Birth</label>
    <input type="text" name="date_of_birth" id="date_of_birth" class="form-control" placeholder="DD-MM-YYYY" maxlength="10" minlength="10" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || (event.charCode == 45) || (event.charCode == 47)' onkeyup="Age_Count(this.value);" value="{{@$rs_record[0]->dob}}"> 
</div>
<div class="col-lg-3 form-group" id="age_value_div">
    <label for="exampleInputEmail1">Age</label>
    <span class="fa fa-asterisk"></span>
    <input type="text" name="age" id="age" class="form-control" maxlength="3" onkeypress='return event.charCode >= 48 && event.charCode <= 57' required value="{{@$rs_record[0]->age}}">
</div>
<div class="col-lg-3 form-group">
    <label>Image (Only:JPG/JPEG/PNG) (Size:20KB)</label>
    <span class="fa fa-asterisk"></span>
    <input type="file" name="image" id="image" class="form-control" accept="image/jpg, image/jpeg, image/png"> 
</div>
<div class="col-lg-3 form-group text-center">
    @php
    $image_path = 'vimage/'.@$rs_record[0]->data_list_id.'/'.@$rs_record[0]->assembly_id.'/'.@$rs_record[0]->assembly_part_id.'/'.@$rs_record[0]->sr_no.'.jpg';
    @endphp
    <img src="{{ route('admin.Master.pollingDayTimesignature',Crypt::encrypt($image_path)) }}" alt="Image" style="height: 110px;width: 150;">
</div>
<div class="col-lg-12 form-group" style="margin-top: 30px;">
    <button type="submit" class="btn btn-primary form-control">Submit</button>
</div>

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
    function Age_Count(dob) {
        if (dob.length == 10) {            
            if(isValidDate(dob)){
                const [day, month, year] = dob.split('-').map(Number);

                const fullYear = year < 100 ? (year > 50 ? 1900 + year : 2000 + year) : year;

                const dobDate = new Date(fullYear, month - 1, day);

                const today = new Date();
                let age = today.getFullYear() - dobDate.getFullYear();
                const hasHadBirthdayThisYear =
                today.getMonth() > dobDate.getMonth() ||
                (today.getMonth() === dobDate.getMonth() && today.getDate() >= dobDate.getDate());
                if (!hasHadBirthdayThisYear) {
                    age--;
                }
                $("#age").val(age);
            }else{
                document.getElementById("age").value = 0;
            }   
        }
    }


    function isValidDate(dateString) {
        const regex = /^(\d{2})-(\d{2})-(\d{4})$/;
        const match = dateString.match(regex);

        if (!match) {
            return false;
        }

        const day = parseInt(match[1], 10);
        const month = parseInt(match[2], 10);
        const year = parseInt(match[3], 10);

        if (month < 1 || month > 12) {
            return false;
        }
        const daysInMonth = new Date(year, month, 0).getDate();
        return day > 0 && day <= daysInMonth;
    }
</script>
<script>
    function check_duplicate_rec(check_type) {
        if(check_type == 1){
            var voter_id_no = $("#voter_id_no").val();
            var rec_id = $("#rec_id").val();
            callAjax(this,'{{ route('admin.voter.check.duplicate.record') }}'+'?voter_id_no='+voter_id_no+'&rec_id='+rec_id+'&check_type='+check_type,'checkDuplicateRecord');
        }else{            
            var name_e = $("#name_english").val();
            var f_name_e = $("#f_h_name_english").val();
            var dob = $("#date_of_birth").val();
            var d_id = $("#district_select_box").val();
            if ((name_e.length > 0) && (f_name_e.length > 0) && (dob.length == 10)) {
                callAjax(this,'{{ route('admin.voter.check.duplicate.record') }}'+'?name_english='+name_e+'&rec_id='+rec_id+'&f_h_name_english='+f_name_e+'&date_of_birth='+dob+'&district_id='+d_id+'&check_type='+check_type,'checkDuplicateRecord1');
            }
        }

    }
</script>  
<script>
    function getTranslatedata() {
        var name_e = $("#name_english").val(); 
        if ($("#name_local_language").val().length !=0) {

        }else{
            $.ajax({
                dataType: "json",
                url: "{{route('admin.voter.getTranslateData')}}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "name_e": name_e
                },
                type: "GET",
                success: function(data) {              
                    if(data.st==1){
                        var n = data.msg.split('|');
                        $("#name_local_language").val(n[0]);

                    } else if(data.st==0){
                        alert('ok');
                    }
                }
            })
        }    
    }
</script>
<script>
    function getTranslatedata2() {
        var f_h_name_e = $("#f_h_name_english").val(); 
        if ($("#f_h_name_local_language").val().length !=0) {

        }else{
            $.ajax({
                dataType: "json",
                url: "{{route('admin.voter.getTranslateData')}}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "name_e": f_h_name_e
                },
                type: "GET",
                success: function(data) {              
                    if(data.st==1){
                        var n = data.msg.split('|');
                        $("#f_h_name_local_language").val(n[0]);

                    }else if(data.st==0){
                        alert('ok');
                    }
                }
            })
        }    
    }
</script>
<script>
    function getTranslatedata3() {
        var f_h_name_e = $("#house_no_english").val(); 
        if ($("#house_no_local_language").val().length !=0) {

        }else{
            $.ajax({
                dataType: "json",
                url: "{{route('admin.voter.getTraDataHouse')}}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "name_e": f_h_name_e
                },
                type: "GET",
                success: function(data) {              
                    if(data.st==1){
                        var n = data.msg.split('|');
                        $("#house_no_local_language").val(n[0]);

                    }else if(data.st==0){
                        alert('ok');
                    }
                }
            })
        }    
    }
</script>                      