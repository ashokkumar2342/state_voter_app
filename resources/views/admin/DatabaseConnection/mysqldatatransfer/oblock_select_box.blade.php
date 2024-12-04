<option value="0">All</option>
@foreach ($blocks as $block)
<option value="{{ $block->id }}">{{ $block->code }}--{{ $block->name_e }}</option> 
@endforeach