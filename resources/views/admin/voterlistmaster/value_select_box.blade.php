<option selected disabled value="{{ Crypt::encrypt(0) }}">Select Voter List</option>
@foreach ($VoterListType as $ListValue)
<option value="{{ Crypt::encrypt($ListValue->id) }}">{{ $ListValue->voter_list_name }}</option>  
@endforeach