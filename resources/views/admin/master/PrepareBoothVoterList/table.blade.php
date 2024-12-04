	 	 
@foreach ($booths as $booth) 
<tr>
<td>
<div class="icheck-primary d-inline">
<input type="checkbox" name="booth[]" value="{{ $booth->id }}" id="{{ $booth->id }}{{ $booth->boothno }}"  class="checkbox">
<label for="{{ $booth->id }}{{ $booth->boothno }}" class="checkbox"></label>
</div> 
</td> 
<td>{{ $booth->boothno}}</td>
<td>{{ $booth->name_e }}</td> 
<td>
@if (empty($booth->d_status))
 	 
@endif
@if ($booth->d_status!='Download')
 	 {{$booth->d_status}}
@endif
@if ($booth->d_status=='Download')
<a target="blank" href="{{ route('admin.booth.voter.list.download',$booth->id) }}">Download</a>
@endif 
	 
</td> 

<td>
@if (empty($booth->d_status))
 	 
@endif
@if ($booth->d_status!='Download')
 	 {{$booth->d_status}}
@endif
@if ($booth->d_status=='Download')
<a target="blank" href="{{ route('admin.booth.voter.list.download.wop',$booth->id) }}">Download</a>
@endif 
	 
</td> 

</tr> 
@endforeach 
 