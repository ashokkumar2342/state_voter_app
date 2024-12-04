{{ Form::label('sub_menu','Block',['class'=>' control-label']) }} <br>
<select class="selectpicker multiselect" multiple data-live-search="true"  name="block[]" id="block_id">
@foreach ($blocks as $blocks)

<option value="{{ $blocks->id }}" {{ in_array($blocks->id,$usersblock)?'selected':'' }}>{{ $blocks->name_l or '' }}</option>
@endforeach 
</select>


