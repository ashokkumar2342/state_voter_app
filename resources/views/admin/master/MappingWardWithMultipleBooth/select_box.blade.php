<div class="col-12">
<div class="form-group">
  <label>Booth</label>
  <select class="duallistbox" multiple="multiple" name="booth[]">
    @foreach ($booths as $booth)
    	<option value="{{ Crypt::encrypt($booth->id) }}" {{ $booth->status?'selected':'' }} >{{ $booth->booth_name }}</option>
    @endforeach 
  </select>
</div>
<!-- /.form-group -->
</div>