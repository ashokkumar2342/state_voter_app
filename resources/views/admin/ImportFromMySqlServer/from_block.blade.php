<option selected disabled value = "0">Select Block / MC's</option>
@foreach ($from_blocks as $from_block)
<option value="{{ $from_block->id }}">{{ $from_block->code }}--{{ $from_block->name_e }}</option>  
@endforeach