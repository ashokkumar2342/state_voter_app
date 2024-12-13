<div class="row">
    <div class="col-12">
        <div class="form-group">
            <label>Ward No.</label>
            <select class="form-control duallistbox" multiple="multiple" name="ward[]">
                @foreach ($wards as $ward)
                    <option value="{{ Crypt::encrypt($ward->id) }}"{{ $ward->status==1?'selected':'' }}>{{ $ward->ward_no }}</option>
                @endforeach 
            </select>
        </div>
    </div>
</div>
<div class="card-footer text-center">
    <button type="submit" class="btn btn-primary btn-block">Save</button>
</div>