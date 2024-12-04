<table class="table">
<thead>
<tr>
<th>Panchayat</th> 
</tr>
</thead>
<tbody>
@foreach ($rs_villages as $villages)
<tr>
	<td>{{ $villages->name_e}}</td>
</tr> 
@endforeach
</tbody>
</table>


