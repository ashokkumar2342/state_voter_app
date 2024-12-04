<option selected disabled value = "0">Select Block / MC's</option>
@foreach ($from_blocks as $from_block)
<option value="{{ $from_block->CODE }}">{{ $from_block->CODE }}--{{ $from_block->NAme_eng }}</option>  
@endforeach