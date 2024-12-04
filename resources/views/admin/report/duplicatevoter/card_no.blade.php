<option selected disabled>Select Duplicate Voter Card No.</option>
@foreach ($voterCardno as $voterCardno)
 	<option value="{{ $voterCardno->voter_card_no }}">{{ $voterCardno->voter_card_no }}</option> 
 @endforeach 