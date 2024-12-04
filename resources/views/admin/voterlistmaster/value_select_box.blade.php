<option selected disabled value = "0">Select Voter List</option>
@foreach ($VoterListType as $ListValue)
<option value="{{ $ListValue->id }}">{{ $ListValue->voter_list_name }}</option>  
@endforeach