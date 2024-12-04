<div class="row">
<div class="col-12">
<div class="form-group">
  <label>Ward No.</label>
  <select class="duallistbox" multiple="multiple" name="ward[]">
    @foreach ($wards as $ward)
       <option value="{{ $ward->id }}"{{ $ward->status==1?'selected':'' }}>{{ $ward->ward_no }}</option>
    @endforeach 
  </select>
</div>
<!-- /.form-group -->
</div>
</div>