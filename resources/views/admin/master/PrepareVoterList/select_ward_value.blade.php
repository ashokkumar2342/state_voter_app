<option selected disabled value="{{ Crypt::encrypt(0) }}">Select Ward</option> 
 @foreach ($WardVillages as $WardVillage)
 	 <option value="{{ Crypt::encrypt($WardVillage->id) }}">{{ $WardVillage->ward_no }} {{ $WardVillage->lock==1?'( Locked )':''  }}</option>
 @endforeach                                     
  



