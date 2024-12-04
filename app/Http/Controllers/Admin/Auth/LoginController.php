<?php

namespace App\Http\Controllers\Admin\Auth;

// use App\Admin;
use App\Http\Controllers\Controller;
// use App\Model\BlocksMc;
// use App\Model\District;
// use App\Model\Village;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin.guest')->except('logout');
    }


  public function index(){
    return redirect()->route('admin.login');    
  }

  public function showLoginForm(){
    return view('admin.auth.login');
  }

  public function login(Request $request){ 
    // return $request;
    $this->validate($request, [
      'email' => 'required', 
      'password' => 'required',
      'captcha' => 'required|captcha' 
    ]);

    $credentials = [
      'email' => $request['email'],
      'password' => $request['password'],
      'status' => 1,
    ]; 
    
    if(auth()->guard('admin')->attempt($credentials)) {
      if (Auth::guard('admin')->user()->user_type==1) {
        return redirect()->route('admin.dashboard');
      }else{
        return redirect()->route('admin.dashboard');
      }         
    } 

    return Redirect()->back()->with(['message'=>'Invalid User or Password','class'=>'error']);    
  }

  public function refreshCaptcha(){  
    return  captcha_img('math');
  }
   
   // Logout method with guard logout for admin only
  public function logout(){
    $this->guard()->logout();
    return redirect()->route('admin.login');
  }
 
    //----------------End--------------

   protected function credentials(Request $request)
   {
       // return $request->only($this->username(), 'password');
       return ['email'=>$request->{$this->username()},'password'=>$request->password,'status'=>'1'];
   }
 
    
    
 //    public function searchVoter(){
 //        return view('admin.auth.search');
 //    }
 //    public function searchVoterform($id)
 //    {
 //      $Districts=District::orderBy('name_e','ASC')->get();
 //      if ($id==1) {
 //      return view('admin.auth.search_form_epic',compact('Districts')); 
 //      }
 //      elseif ($id==2) {
 //      return view('admin.auth.search_form',compact('Districts')); 
 //      }
      
 //    }
 //    public function searchDisBlock(Request $request)
 //    {
 //       try{
           
 //          $BlocksMcs=BlocksMc::where('districts_id',$request->id)->get(); 
 //          return view('admin.master.block.value_select_box',compact('BlocksMcs'));
 //        } catch (Exception $e) {
            
 //        }
 //    }
 //    public function searchBlockVillage(Request $request)
 //    {
 //       try{ 
          
 //          $blockId = $request->id;
 //          $myquery = "Select * from `blocks_mcs` where `id` = $blockId;";
 //          $rs_blocks = DB::select(DB::raw($myquery));
 //          $block_type = $rs_blocks[0]->block_mc_type_id;
 //          if($block_type>1){
 //            $myquery = "select `id`, `ward_no` as `code`, `name_e`, `is_locked` from `ward_villages` where `blocks_id` = $blockId order by `code`";
 //            $Villages = DB::select(DB::raw($myquery));
 //          }else{
 //            $Villages=Village::where('blocks_id',$request->id)->orderBy('name_e','ASC')->get();   
 //          }

          
 //          return view('admin.master.village.value_select_box',compact('Villages'));
 //        } catch (Exception $e) {
            
 //        }
 //    }


 //    public function searchVoterFilter(Request $request ,$cond)
 //    {
 //      if ($cond==1) { 
 //        $rules=[ 
 //              'voter_card_no' => 'required', 
 //        ];
 //      }else{
 //        $rules=[ 
 //               'district' => 'required', 
 //               'v_name' => 'required|string|min:2|max:50',
 //               // 'father_name' => 'required|string|min:2|max:50',
 //        ];
 //      }

 //        $validator = Validator::make($request->all(),$rules);
 //        if ($validator->fails()) {
 //            $errors = $validator->errors()->all();
 //            $response=array();
 //            $response["status"]=0;
 //            $response["msg"]=$errors[0];
 //            return response()->json($response);// response as json
 //        }
 //        $search_condition = "";
 //        $search_by = $cond;
 //        if($search_by ==1){
 //          $search_condition = " where `v`.`voter_card_no` = '$request->voter_card_no'";
 //        }else{

 //          $blockId = $request->block;
 //          $village_id = $request->village;
 //          $myquery = "Select * from `blocks_mcs` where `id` = $blockId;";
 //          $rs_blocks = DB::select(DB::raw($myquery));
 //          $block_type = $rs_blocks[0]->block_mc_type_id;
 //          if($block_type>1){
 //            if ($village_id>0){
 //              $search_condition = $search_condition." where `v`.`ward_id` = $village_id ";
 //            }
 //          }else{
            
 //            if ($village_id>0){
 //              $search_condition = $search_condition." where `v`.`assembly_part_id` in (select `id` from `assembly_parts` where `village_id` = $village_id) ";
 //            }  
 //          }
          
          
 //          $voter_name = $request->v_name;
 //          $search_condition = $search_condition." and `v`.`name_e` like '$voter_name%' ";

 //          $voter_name = $request->father_name;
 //          if($voter_name!=''){
 //            $search_condition = $search_condition." and `v`.`father_name_e` like '$voter_name%' ";  
 //          }
          

 //          $mobile_no = $request->mobile_no;
 //          if($mobile_no!=''){
 //            $search_condition = $search_condition." and `v`.`mobile_no` like '%$mobile_no' ";
 //          }

 //          $age = $request->age;
 //          if($age!='0'){
 //            $search_condition = $search_condition." and `v`.`age` between $age ";
 //          }  

 //        }
        
 //        $myquery = "select `v`.`name_e`, `v`.`father_name_e`, `r`.`relation_e`, `v`.`age`, `g`.`genders`, `v`.`voter_card_no`, '' as `block_name`,
 //          '' as `village_name`, `v`.`mobile_no`, '' as `ward_no`, `asmb`.`part_no` as `booth_no_comp`, '' as `polling_booth_name`, `v`.`print_sr_no`  
 //          from `voters` `v` 
 //          inner join `relation` `r` on `r`.`id` = `v`.`relation`
 //          inner join `genders` `g` on `g`.`id` = `v`.`gender_id` 
 //          inner join `assembly_parts` `asmb` on `asmb`.`id` = `v`.`assembly_part_id` $search_condition order by `v`.`name_e`";

 //        // return $myquery;
 //        $voters= DB::select(DB::raw($myquery));

 //        $response= array();                       
 //        $response['status']= 1;                       
 //        $response['data']=view('admin.auth.result',compact('voters'))->render();
 //        return $response;
       
 //    }


 //    public function searchVoterFilter_old(Request $request ,$cond)
 //    {
 //      if ($cond==1) { 
 //        $rules=[ 
 //              'voter_card_no' => 'required', 
 //        ];
 //      }else{
 //        $rules=[ 
 //               'district' => 'required', 
 //               'v_name' => 'required|string|min:2|max:50',
 //               'father_name' => 'required|string|min:2|max:50',
 //        ];
 //      }

 //        $validator = Validator::make($request->all(),$rules);
 //        if ($validator->fails()) {
 //            $errors = $validator->errors()->all();
 //            $response=array();
 //            $response["status"]=0;
 //            $response["msg"]=$errors[0];
 //            return response()->json($response);// response as json
 //        }
 //        $search_condition = "";
 //        $search_by = $cond;
 //        if($search_by ==1){
 //          $search_condition = " where `v`.`voter_card_no` = '$request->voter_card_no'";
 //        }else{
 //          $search_condition = " where `v`.`district_id` = $request->district ";
 //          $block_id = $request->block;
 //          if ($block_id>0){
 //            $search_condition = $search_condition." and `vil`.`blocks_id` = $block_id ";
 //          }
 //          $village_id = $request->village;
 //          if ($village_id>0){
 //            $search_condition = $search_condition." and `v`.`village_id` = $village_id ";
 //          }
          
 //          $voter_name = $request->v_name;
 //          $search_condition = $search_condition." and `v`.`name_e` like '$voter_name%' ";

 //          $voter_name = $request->father_name;
 //          $search_condition = $search_condition." and `v`.`father_name_e` like '$voter_name%' ";

 //          $mobile_no = $request->mobile_no;
 //          if($voter_name!=''){
 //            $search_condition = $search_condition." and `v`.`mobile_no` like '%$mobile_no' ";
 //          }

 //          $age = $request->age;
 //          if($age!='0'){
 //            $search_condition = $search_condition." and `v`.`age` between $age ";
 //          }  

 //        }
        
 //        $myquery = "select `v`.`name_e`, `v`.`father_name_e`, `r`.`relation_e`, `v`.`age`, `g`.`genders`, `v`.`voter_card_no`, `b`.`name_e` as `block_name`,
 //          `vil`.`name_e` as `village_name`, `v`.`mobile_no`, `wv`.`ward_no`, concat(`pb`.`booth_no`, `pb`.`booth_no_c`) as `booth_no_comp`, `pb`.`name_e` as `polling_booth_name`, `v`.`print_sr_no`  
 //          from `voters` `v` 
 //          inner join `villages` `vil` on `vil`.`id` = `v`.`village_id`
 //          inner join `relation` `r` on `r`.`id` = `v`.`relation`
 //          inner join `genders` `g` on `g`.`id` = `v`.`gender_id` 
 //          inner join `blocks_mcs` `b` on `b`.`id` = `vil`.`blocks_id`
 //          inner join `ward_villages` `wv` on `wv`.`id` = `v`.`ward_id`
 //          left join `polling_booths` `pb` on `pb`.`id` = `v`.`booth_id` $search_condition order by `v`.`name_e`";

 //        // return $myquery;
 //        $voters= DB::select(DB::raw($myquery));

 //        $response= array();                       
 //        $response['status']= 1;                       
 //        $response['data']=view('admin.auth.result',compact('voters'))->render();
 //        return $response;
       
 //    }

    
    
  

    
 //    // defining auth  guard
    protected function guard()
    {
        return Auth::guard('admin');
    }
 //    public function forgetPassword()
 //    {
 //        return view('admin.auth.forget_password');
 //    }
 //    public function forgetPasswordSendLink(Request $request)
 //    {
 //        $AppUsers=new Admin();
 //        $u_detail=$AppUsers->getdetailbyemail($request->email);
 //        $up_u=array();
 //        $up_u['token'] = str_random(64);        
 //        $AppUsers->updateuserdetail($up_u,$u_detail->user_id);      
 //        $up_u['name']=$u_detail->name;
 //        $up_u['email']=$u_detail->email;
 //        $user=$u_detail->email;
 //        // $up_u['otp']=$up_u['otp'];
 //        $up_u['logo']=url("img/logo.png");
 //        $up_u['link']=url("passwordreset/reset/".$up_u['token']);


 //        $response=array();
 //        $response['status']=1;
 //        $response['msg']='Reset Link Sent successfully';
 //        return $response;

 //    }
    
}
