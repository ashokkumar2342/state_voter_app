<div class="col-12">
<div class="form-group">
  <label>Panchayat</label>
  <select class="duallistbox" multiple="multiple" name="village[]">
    @foreach ($villages as $village)
       <option value="{{ $village->id }}"{{ $village->status == 1?'selected':'' }}>{{ $village->bl_name }} - {{ $village->name_e }}</option>
    @endforeach 
  </select>
</div>
<!-- /.form-group -->
</div>