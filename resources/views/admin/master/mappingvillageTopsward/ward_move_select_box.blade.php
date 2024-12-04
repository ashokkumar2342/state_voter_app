<div class="col-12">
<div class="form-group">
  <label>Ward No.</label>
  <select class="duallistbox" multiple="multiple" name="village[]">
    @foreach ($villages as $village)
       <option value="{{ $village->id }}"{{ $village->status==1?'selected':'' }}>{{ $village->ps_ward_name }}</option>
    @endforeach 
  </select>
</div>
<!-- /.form-group -->
</div>