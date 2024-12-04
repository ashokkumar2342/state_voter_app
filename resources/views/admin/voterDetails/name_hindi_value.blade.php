@if ($condition_type==1)
<label for="exampleInputEmail1">Name (H)</label>
<span class="fa fa-asterisk"></span>
<input type="text" name="name_local_language" class="form-control" value="{{ @$name_l }}"> 
@endif
@if ($condition_type==2)
<label for="exampleInputEmail1">F/H Name (H)</label>
<span class="fa fa-asterisk"></span>
<input type="text" name="f_h_name_local_language" class="form-control" value="{{ @$name_l }}"> 
@endif
@if ($condition_type==3)
<label for="exampleInputEmail1">House No.(H)</label>
<span class="fa fa-asterisk"></span>
<input type="text" name="house_no_local_language" class="form-control" value="{{ @$name_l }}"> 
@endif

 