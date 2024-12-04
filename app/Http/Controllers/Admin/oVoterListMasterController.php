<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\DefaultValue;
use App\Model\VoterListMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VoterListMasterController extends Controller
{
	public function index()
	{
		$VoterListMasters=VoterListMaster::orderBy('id','ASC')->get(); 
		return view('admin.voterlistmaster.index',compact('VoterListMasters'));
	}
	public function store(Request $request,$id=null)
	{    
		$rules=[

// 'syllabus' => 'required', 
		];

		$validator = Validator::make($request->all(),$rules);
		if ($validator->fails()) {
			$errors = $validator->errors()->all();
			$response=array();
			$response["status"]=0;
			$response["msg"]=$errors[0];
return response()->json($response);// response as json
}
else {
	$voterlistmaster=VoterListMaster::firstOrNew(['id'=>$id]);
	$voterlistmaster->voter_list_name=$request->voter_list_name;
	$voterlistmaster->voter_list_type=$request->voter_list_type;
	$voterlistmaster->year_publication=$request->publication_year;
	$voterlistmaster->year_base=$request->base_year;
	$voterlistmaster->date_publication=$request->date_of_publication;
	$voterlistmaster->date_base=$request->base_date;
	$voterlistmaster->remarks1=$request->remarks1;
	$voterlistmaster->remarks2=$request->remarks2;
	$voterlistmaster->remarks3=$request->remarks3;
	if (empty($request->is_supplement)) {
		$voterlistmaster->is_supplement=0;  
	}
	elseif (!empty($request->is_supplement)) {
		$voterlistmaster->is_supplement=$request->is_supplement;  
	} 
	$voterlistmaster->save();
	$response=['status'=>1,'msg'=>'Submit Successfully'];
	return response()->json($response);
}
}
public function default($id)
{
	$VoterListMaster =VoterListMaster::all(); 
	foreach ($VoterListMaster as  $value) {
		$VoterListMaster =VoterListMaster::find($value->id);
		$VoterListMaster->status=0;
		$VoterListMaster->save(); 
	}
	$VoterListMaster =VoterListMaster::find($id); 
	$VoterListMaster->status=1;
	$VoterListMaster->save();
	return  redirect()->back()->with(['message'=>'Default Value Set Successfully','class'=>'success']);	 
}
public function edit($id)
{
	$VoterListMaster=VoterListMaster::find($id); 
	return view('admin.voterlistmaster.edit',compact('VoterListMaster'));
}

//-------------------VoterListDefaultValue-VoterListDefaultValue----VoterListDefaultValue-
public function VoterListDefaultValue()
{
	$VoterListDefaultValue=DefaultValue::first(); 
	return view('admin.voterlistmaster.default_value',compact('VoterListDefaultValue'));
}
public function VoterListDefaultValueStore(Request $request ,$id=null)
{
	  
	$VoterListDefaultValue=DefaultValue::firstOrNew(['id'=>$id]);
	
	if (empty($request->page_break)) {
	$VoterListDefaultValue->page_break=0;  
	}
	elseif (!empty($request->page_break)) {
	$VoterListDefaultValue->page_break=$request->page_break;  
	}
	if (empty($request->page_blank)) {
	$VoterListDefaultValue->page_blank=0;  
	}
	elseif (!empty($request->page_blank)) {
	$VoterListDefaultValue->page_blank=$request->page_blank;  
	} 
	$VoterListDefaultValue->status=1;
	$VoterListDefaultValue->save();
	$response=['status'=>1,'msg'=>'Submit Successfully'];
	return response()->json($response);
}
 

}

