<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
// use PDF;
// use Symfony\Component\HttpKernel\DataCollector\collect;
use App\Helper\MyFuncs;
use App\Helper\SelectBox;
use Session;

class AccountController extends Controller
{
    protected $e_controller = "AccountController";

    public function changePassword()
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(8);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            return view('admin.account.change_password');
        } catch (\Exception $e) {
            $e_method = "changePassword";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function changePasswordStore(Request $request)
    { 
        try {
            $permission_flag = MyFuncs::isPermission_route(8);
            if(!$permission_flag){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);// response as json
            }

            $rules=[
                'oldpassword'=> 'required',
                'password'=> 'required',
                'passwordconfirmation'=> 'required|same:password',
            ];
            $customMessages = [
                'oldpassword.required'=> 'Please Enter Old Password',                
                
                'password.required'=> 'Please Enter New Password',
                
                'passwordconfirmation.required'=> 'Please Enter Confirm Password',
                'passwordconfirmation.same'=> 'New and Confirm Password Mismatch',
            ];
            $validator = Validator::make($request->all(),$rules, $customMessages);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response=array();
                $response["status"]=0;
                $response["msg"]=$errors[0];
                return response()->json($response);// response as json
            }        
            $user=Auth::guard('admin')->user();
            $userid = $user->id; 

            $key = Session::get('CryptoRandom');
            $iv = Session::get('CryptoRandomInfo');
            
            $data = hex2bin($request['password']);
            $decryptedpass = openssl_decrypt($data, 'DES-CBC', $key, OPENSSL_RAW_DATA, $iv);
            
            $c_data = hex2bin($request['passwordconfirmation']);
            $c_decryptedpass = openssl_decrypt($c_data, 'DES-CBC', $key, OPENSSL_RAW_DATA, $iv);
            
            $o_data = hex2bin($request['oldpassword']);
            $o_decryptedpass = openssl_decrypt($o_data, 'DES-CBC', $key, OPENSSL_RAW_DATA, $iv);
            
            $password_strength = MyFuncs::check_password_strength($decryptedpass, $userid);
            if($password_strength != ''){
                $response=['status'=>0,'msg'=>$password_strength];
                return response()->json($response);// response as json
            }

            $from_ip = MyFuncs::getIp();

            if(password_verify($o_decryptedpass,$user->password)){
                if ($o_decryptedpass == $decryptedpass) {
                    $response=['status'=>0,'msg'=>'Old Password And New Password Cannot Be Same'];
                    return response()->json($response);
                }else{
                    $en_password = bcrypt($decryptedpass); 
                    DB::select(DB::raw("UPDATE `admins` set `password` = '$en_password', `password_expire_on` = date_add(curdate(), INTERVAL 15 DAY) where `id` = $userid limit 1;"));

                    DB::select(DB::raw("INSERT into `password_change_history` (`user_id`, `old_password`, `new_password`, `from_ip`, `log_time`, `log_type`) values ($userid, '', '$en_password', '$from_ip', now(), 1);"));

                    $response=['status'=>1,'msg'=>'Password Changed Successfully'];
                    return response()->json($response);// response as json 
                }
            }else{               
                $response=['status'=>0,'msg'=>'Old Password Is Not Correct'];
                return response()->json($response);// response as json
            }
        } catch (\Exception $e) {
            $e_method = "changePasswordStore";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }        
    }

    Public function create_form(Request $request)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(2);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $admin=Auth::guard('admin')->user();       
            $user_role = $admin->role_id;
            $roles =DB::select(DB::raw("SELECT `id`, `name` from `roles` where `id`  > $user_role Order By `name`;"));
            return view('admin.account.form',compact('roles'));
        } catch (\Exception $e) {
            $e_method = "create_form";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    } 

    Public function store(Request $request)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(2);
            if(!$permission_flag){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }
            $rules=[
                'first_name' => 'required|min:3|max:50',             
                'email' => 'required|email',
                "mobile" => 'required|numeric|digits:10',
                "role_id" => 'required',
                "password" => 'required', 
            ];

            $customMessages = [
                'first_name.required'=> 'Please Enter First Name',                
                'first_name.min'=> 'First Name Should Be Minimum of 5 Character',                
                'first_name.max'=> 'First Name Should Be Maximum of 50 Character',

                'email.required'=> 'Please Enter Email',
                'email.email'=> 'Please Enter Email in Email Format',

                'mobile.required'=> 'Please Enter Mobile No.',
                'mobile.numeric'=> 'Mobile No. Should Be Numeric Only',
                'mobile.digits'=> 'Mobile No. Should Be of 10 Digits',
                
                'role_id.required'=> 'Please Select User Role',
                
                'password.required'=> 'Please Enter Password',
            ];
            $validator = Validator::make($request->all(),$rules, $customMessages);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response=array();
                $response["status"]=0;
                $response["msg"]=$errors[0];
                return response()->json($response);// response as json
            }

            $admin = Auth::guard('admin')->user();
            $user_id = $admin->id;
            $role_id = $admin->role_id;
            
            $from_ip = MyFuncs::getIp();

            $user_name = substr(MyFuncs::removeSpacialChr($request->first_name), 0, 50);
            $email_id = substr(MyFuncs::removeSpacialChr($request->email), 0, 50);
            $mobile = substr(MyFuncs::removeSpacialChr($request->mobile), 0, 10);
            $new_role_id = intval(Crypt::decrypt($request->role_id));

            if($role_id >= $new_role_id){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);    
            }

            $account_id = 0;
            $email_available = MyFuncs::check_emailid_user($account_id, $email_id);
            if($email_available == 0){
                $response=['status'=>0,'msg'=>'Email Id Already Used By Other User'];
                return response()->json($response);
            }

            $mobile_available = MyFuncs::check_mobile_user($account_id, $mobile);
            if($mobile_available == 0){
                $response=['status'=>0,'msg'=>'Mobile No. Already Used By Other User'];
                return response()->json($response);
            }

            $key = Session::get('CryptoRandom');
            $iv = Session::get('CryptoRandomInfo');
            
            $data = hex2bin($request['password']);
            $decryptedpass = openssl_decrypt($data, 'DES-CBC', $key, OPENSSL_RAW_DATA, $iv);
            
            $password_strength = MyFuncs::check_password_strength($decryptedpass, 0);
            if($password_strength != ''){
                $response=['status'=>0,'msg'=>$password_strength];
                return response()->json($response);// response as json
            }

            
            $password=bcrypt($decryptedpass);

            $for_district = 0;
            if($role_id == 2){
                $rs_fetch = DB::select(DB::raw("SELECT `district_id` from `user_district_assigns` where `user_id` = $user_id and `status` = 1 limit 1;"));
                if(count($rs_fetch)>0){
                    $for_district = $rs_fetch[0]->district_id;
                }
            }elseif($role_id == 3){
                $rs_fetch = DB::select(DB::raw("SELECT `district_id` from `user_block_assigns` where `user_id` = $user_id and `status` = 1 limit 1;"));
                if(count($rs_fetch)>0){
                    $for_district = $rs_fetch[0]->district_id;
                }
            }
            
            $accounts = DB::select(DB::raw("INSERT into `admins` (`first_name`, `role_id` , `email` , `password` , `mobile` , `password_plain` , `status`, `created_by`, `for_district`) values ('$user_name' , $new_role_id , '$email_id' , '$password' , '$mobile' , '' , '1', $user_id, $for_district);"));

            $new_user_id = 0;
            $rs_fetch = DB::select(DB::raw("SELECT `id` from `admins` where `email` = '$email_id' limit 1;"));
            if(count($rs_fetch)>0){
                $new_user_id = $rs_fetch[0]->id;
            }

            $rs_update = DB::select(DB::raw("INSERT into `password_change_history` (`user_id`, `old_password`, `new_password`, `from_ip`, `log_time`, `log_type`) values ($new_user_id, '', '$password', '$from_ip', now(), 0);"));
            
            $response=['status'=>1,'msg'=>'Account Created Successfully'];
            return response()->json($response);
        } catch (\Exception $e) {
            $e_method = "store";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }   
    }   

    Public function DistrictsAssign()
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(3);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $user_id = MyFuncs::getUserId();
            $role_id = 2;
            $users = SelectBox::get_user_list_v1($role_id, $user_id); 
            return view('admin.account.assign.district.index',compact('users'));
        } catch (\Exception $e) {
            $e_method = "DistrictsAssign";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    Public function StateDistrictsSelect(Request $request)
    {  
        try {
            $permission_flag = MyFuncs::isPermission_route(3);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            
            $rs_district = SelectBox::get_district_access_list_v1();

            $r_user_id = intval(Crypt::decrypt($request->id));

            $DistrictBlockAssigns = DB::select(DB::raw("SELECT `uda`.`id`, `dis`.`name_e` from `user_district_assigns` `uda` inner join `districts` `dis` on `dis`.`id` = `uda`.`district_id` where `uda`.`status` = 1 and `uda`.`user_id` = $r_user_id;"));
            
            $data= view('admin.account.assign.district.select_box',compact('DistrictBlockAssigns', 'rs_district'))->render(); 
            return response($data);
        } catch (\Exception $e) {
            $e_method = "StateDistrictsSelect";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    Public function DistrictsAssignStore(Request $request)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(3);
            if(!$permission_flag){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }
            
            $rules=[
                'district' => 'required', 
                'user' => 'required',  
            ]; 
            $customMessages = [
                'district.required'=> 'Please Select District',                
                'user.required'=> 'Please Select User',
            ];
            $validator = Validator::make($request->all(),$rules, $customMessages);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response=array();
                $response["status"]=0;
                $response["msg"]=$errors[0];
                return response()->json($response);// response as json
            }
            $district_id = intval(Crypt::decrypt($request->district));
            $r_user_id = intval(Crypt::decrypt($request->user));

            $user_id = MyFuncs::getUserId();

            $rs_save = DB::select(DB::raw("call `up_save_assign_district` ($r_user_id, $district_id, $user_id);"));  
            $response['msg'] = $rs_save[0]->result;
            $response['status'] = $rs_save[0]->s_status;
            return response()->json($response);
        } catch (\Exception $e) {
            $e_method = "DistrictsAssignStore";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }  
    }

    public function DistrictsAssignDelete($id)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(3);
            if(!$permission_flag){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }
            $assigned_id = intval(Crypt::decrypt($id));
            $rs_delete = DB::select(DB::raw("UPDATE `user_district_assigns` set `status` = 0 where `id` = $assigned_id limit 1;"));
            $response['msg'] = 'District Removed Successfully';
            $response['status'] = 1;
            return response()->json($response);
        } catch (\Exception $e) {
            $e_method = "DistrictsAssignDelete";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

//     //-----------block-assign----------------------------------//

    Public function BlockAssign()
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(4);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $user_id = MyFuncs::getUserId();
            $role_id = 3;
            $users = SelectBox::get_user_list_v1($role_id, $user_id); 
            return view('admin.account.assign.block.index',compact('users'));

        } catch (\Exception $e) {
            $e_method = "BlockAssign";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    Public function DistrictBlockAssign(Request $request)
    { 
        try {
            $permission_flag = MyFuncs::isPermission_route(4);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            
            $rs_district = SelectBox::get_district_access_list_v1();

            $r_user_id = intval(Crypt::decrypt($request->id));

            $DistrictBlockAssigns = DB::select(DB::raw("SELECT `uda`.`id`, `dis`.`name_e` from `user_block_assigns` `uda` inner join `blocks_mcs` `dis` on `dis`.`id` = `uda`.`block_id` where `uda`.`status` = 1 and `uda`.`user_id` = $r_user_id;"));
            
            $data= view('admin.account.assign.block.select_box',compact('DistrictBlockAssigns','rs_district'))->render(); 
            return response($data);
        } catch (\Exception $e) {
            $e_method = "DistrictBlockAssign";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    Public function DistrictBlockAssignStore(Request $request)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(4);
            if(!$permission_flag){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }

            $rules=[
                'district' => 'required', 
                'block' => 'required', 
                'user' => 'required',  
            ]; 
            $customMessages = [
                'district.required'=> 'Please Select District',                
                'block.required'=> 'Please Select Block/MC',
                'user.required'=> 'Please Select User',
            ];
            $validator = Validator::make($request->all(),$rules, $customMessages);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response=array();
                $response["status"]=0;
                $response["msg"]=$errors[0];
                return response()->json($response);// response as json
            }

            $district_id = intval(Crypt::decrypt($request->district));
            $block_id = intval(Crypt::decrypt($request->block));
            $r_user_id = intval(Crypt::decrypt($request->user));

            $permission_flag = MyFuncs::check_district_access($district_id);
            if($permission_flag == 0){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }            

            $permission_flag = MyFuncs::check_block_access($block_id);
            if($permission_flag == 0){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }            

            $user_id = MyFuncs::getUserId();

            $rs_save = DB::select(DB::raw("call `up_save_assign_block` ($r_user_id, $block_id, $user_id);"));  
            $response['msg'] = $rs_save[0]->result;
            $response['status'] = $rs_save[0]->s_status;
            return response()->json($response);

        } catch (\Exception $e) {
            $e_method = "DistrictBlockAssignStore";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function DistrictBlockAssignDelete($id)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(4);
            if(!$permission_flag){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }
            $assigned_id = intval(Crypt::decrypt($id));
            $rs_delete = DB::select(DB::raw("UPDATE `user_block_assigns` set `status` = 0 where `id` = $assigned_id limit 1;"));
            $response['msg'] = 'Block/MC Removed Successfully';
            $response['status'] = 1;
            return response()->json($response);
        } catch (\Exception $e) {
            $e_method = "DistrictBlockAssignDelete";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

// ///------village-Assign-----------------------------------
    Public function VillageAssign()
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(5);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $user_id = MyFuncs::getUserId();
            $role_id = 4;
            $users = SelectBox::get_user_list_v1($role_id, $user_id); 
            return view('admin.account.assign.village.index',compact('users'));
        } catch (\Exception $e) {
            $e_method = "VillageAssign";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    Public function DistrictBlockVillageAssign(Request $request)
    { 
        try {
            $permission_flag = MyFuncs::isPermission_route(5);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            
            $rs_district = SelectBox::get_district_access_list_v1();

            $r_user_id = intval(Crypt::decrypt($request->id));

            $DistrictBlockAssigns = DB::select(DB::raw("SELECT `uda`.`id`, `dis`.`name_e` as `block_name`, `vil`.`name_e` as `vil_name` from `user_village_assigns` `uda` inner join `blocks_mcs` `dis` on `dis`.`id` = `uda`.`block_id` inner join `villages` `vil` on `vil`.`id` = `uda`.`village_id` where `uda`.`status` = 1 and `uda`.`user_id` = $r_user_id;"));

            $data= view('admin.account.assign.village.select_box',compact('DistrictBlockAssigns','rs_district'))->render(); 
            return response($data);
        } catch (\Exception $e) {
            $e_method = "DistrictBlockVillageAssign";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    Public function DistrictBlockVillageAssignStore(Request $request)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(5);
            if(!$permission_flag){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }

            $rules=[
                'district' => 'required', 
                'block' => 'required',
                'village' => 'required', 
                'user' => 'required',  
            ]; 
            $customMessages = [
                'district.required'=> 'Please Select District',                
                'block.required'=> 'Please Select Block/MC',
                'village.required'=> 'Please Select Panchayat/MC',
                'user.required'=> 'Please Select User',
            ];
            $validator = Validator::make($request->all(),$rules, $customMessages);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response=array();
                $response["status"]=0;
                $response["msg"]=$errors[0];
                return response()->json($response);// response as json
            }

            $district_id = intval(Crypt::decrypt($request->district));
            $block_id = intval(Crypt::decrypt($request->block));
            $village_id = intval(Crypt::decrypt($request->village));
            $r_user_id = intval(Crypt::decrypt($request->user));

            $permission_flag = MyFuncs::check_district_access($district_id);
            if($permission_flag == 0){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }            

            $permission_flag = MyFuncs::check_block_access($block_id);
            if($permission_flag == 0){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }            

            $permission_flag = MyFuncs::check_village_access($village_id);
            if($permission_flag == 0){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }            

            $user_id = MyFuncs::getUserId();

            $rs_save = DB::select(DB::raw("call `up_save_assign_village` ($r_user_id, $village_id, $user_id);"));  
            $response['msg'] = $rs_save[0]->result;
            $response['status'] = $rs_save[0]->s_status;
            return response()->json($response);

        } catch (\Exception $e) {
            $e_method = "DistrictBlockVillageAssignStore";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function DistrictBlockVillageAssignDelete($id)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(5);
            if(!$permission_flag){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }
            $assigned_id = intval(Crypt::decrypt($id));
            $rs_delete = DB::select(DB::raw("UPDATE `user_village_assigns` set `status` = 0 where `id` = $assigned_id limit 1;"));
            $response['msg'] = 'Panchayat/MC Removed Successfully';
            $response['status'] = 1;
            return response()->json($response);
        } catch (\Exception $e) {
            $e_method = "DistrictBlockVillageAssignDelete";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    Public function usr_lst_index()
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(1);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $user_id = MyFuncs::getUserId();
            $role_id = 0;
            $accounts = DB::select(DB::raw("SELECT `a`.`id`, `a`.`first_name`, `a`.`email`, `a`.`mobile`, `a`.`status`, `r`.`name` from `admins` `a`Inner Join `roles` `r` on `a`.`role_id` = `r`.`id` where `a`.`created_by` = $user_id Order By `a`.`first_name`;")); 
            return view('admin.account.list',compact('accounts'));
        } catch (\Exception $e) {
            $e_method = "usr_lst_index";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    Public function change_status($id)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(1);
            if(!$permission_flag){
                return redirect()->back()->with(['class'=>'error','message'=>'Something Went Wrong']);
            }

            $acc_id = intval(Crypt::decrypt($id));

            $user_id = MyFuncs::getUserId();
            
            $rs_fetch = DB::select(DB::raw("SELECT `usr`.`id` from `admins` `usr` where `usr`.`id` = $acc_id and `usr`.`created_by` = $user_id limit 1;"));
            if(count($rs_fetch) == 0){
                return redirect()->back()->with(['class'=>'error','message'=>'Something Went Wrong']);   
            }

            $rs_update = DB::select(DB::raw("call `up_toggle_user_status`($acc_id);"));
            return redirect()->back()->with(['class'=>'success','message'=>'Status Changed Successfully']);
        } catch (\Exception $e) {
            $e_method = "change_status";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function resetPassWord()
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(9);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $user_id = MyFuncs::getUserId();
            $role_id = 0;
            $users = SelectBox::get_user_list_v1($role_id, $user_id); 
            return view('admin.account.reset_password',compact('users'));
        } catch (\Exception $e) {
            $e_method = "resetPassWord";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function resetPassWordChange(Request $request)
    { 
        try {
            $permission_flag = MyFuncs::isPermission_route(9);
            if(!$permission_flag){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);// response as json
            }

            $rules=[
                'user'=> 'required',
                'password'=> 'required',
                'passwordconfirmation'=> 'required|same:password',
            ];
            $customMessages = [
                'user.required'=> 'Please Select User',                
                
                'password.required'=> 'Please Enter New Password',
                
                'passwordconfirmation.required'=> 'Please Enter Confirm Password',
                'passwordconfirmation.same'=> 'New and Confirm Password Mismatch',
            ];
            $validator = Validator::make($request->all(),$rules, $customMessages);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response=array();
                $response["status"]=0;
                $response["msg"]=$errors[0];
                return response()->json($response);// response as json
            }        
            
            $user_id = MyFuncs::getUserId();

            $r_user_id = intval(Crypt::decrypt($request->user));
            $rs_fetch = DB::select(DB::raw("SELECT `usr`.`id` from `admins` `usr` where `usr`.`id` = $r_user_id and `usr`.`created_by` = $user_id limit 1;"));
            if(count($rs_fetch) == 0){
                $response=['status'=>0,'msg'=>"Something Went Wrong"];
                return response()->json($response);
            }

            $key = Session::get('CryptoRandom');
            $iv = Session::get('CryptoRandomInfo');
            
            $data = hex2bin($request['password']);
            $decryptedpass = openssl_decrypt($data, 'DES-CBC', $key, OPENSSL_RAW_DATA, $iv);
            
            $c_data = hex2bin($request['passwordconfirmation']);
            $c_decryptedpass = openssl_decrypt($c_data, 'DES-CBC', $key, OPENSSL_RAW_DATA, $iv);
            
            $password_strength = MyFuncs::check_password_strength($decryptedpass, $r_user_id);
            if($password_strength != ''){
                $response=['status'=>0,'msg'=>$password_strength];
                return response()->json($response);// response as json
            }

            $from_ip = MyFuncs::getIp();

            $en_password = bcrypt($decryptedpass); 
            $rs_update = DB::select(DB::raw("UPDATE `admins` set `password` = '$en_password', `password_expire_on` = curdate() where `id` = $r_user_id limit 1;"));

            $response=['status'=>1,'msg'=>'Password Reset Successfully'];
            return response()->json($response);
        } catch (\Exception $e) {
            $e_method = "resetPassWordChange";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }        
    }
    
    public function exception_handler()
    {
        try {

        } catch (\Exception $e) {
            $e_method = "imageShowPath";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    

    // Public function usr_edit($id)
    // {
    //     try {
    //         $permission_flag = MyFuncs::isPermission_route(1);
    //         if(!$permission_flag){
    //             return view('admin.common.error_popup');
    //         }

    //         $acc_id = intval(Crypt::decrypt($id));
            
    //         $user_id = MyFuncs::getUserId();
            
    //         $rs_fetch = DB::select(DB::raw("SELECT `usr`.`id` from `admins` `usr` where `usr`.`id` = $acc_id and `usr`.`created_by` = $user_id limit 1;"));
    //         if(count($rs_fetch) == 0){
    //             return view('admin.common.error_popup');   
    //         }

    //         $account = DB::select(DB::raw("SELECT `id`, `first_name`, `email`, `mobile` from `admins` where `id` = $acc_id limit 1;"));
    //         if(count($account) == 0){
    //             return view('admin.common.error_popup');   
    //         }
    //         return view('admin.account.edit',compact('account'));

    //     } catch (\Exception $e) {
    //         $e_method = "usr_edit";
    //         return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    //     } 
    // }


//     Public function store(Request $request){ 
//         $rules=[
//             'first_name' => 'required|string|min:3|max:50',             
//             'email' => 'required|email|unique:admins',
//             "mobile" => 'required|unique:admins|numeric|digits:10',
//             "role_id" => 'required',
//             "password" => 'required|min:6|max:15', 
//         ];

//         $validator = Validator::make($request->all(),$rules);
//         if ($validator->fails()) {
//             $errors = $validator->errors()->all();
//             $response=array();
//             $response["status"]=0;
//             $response["msg"]=$errors[0];
//             return response()->json($response);// response as json
//         }
        
//         $admin=Auth::guard('admin')->user(); 
//         $user_id = $admin->id;
//         $en_password = bcrypt($request['password']);
//         DB::select(DB::raw("Insert Into `admins` (`first_name`, `last_name`, `email`, `mobile`, `password`, `password_plain`, `role_id`, `created_by`) Values ('$request->first_name', '$request->last_name', '$request->email', '$request->mobile', '$en_password','',$request->role_id, $user_id);"));

//         $response=['status'=>1,'msg'=>'Account Created Successfully'];
//         return response()->json($response);   
//     }

    

    

//     Public function update(Request $request, $id){

//         $rules=[
//             'first_name' => 'required|string|min:3|max:50',
//             "mobile" => 'required|numeric|digits:10',
//             "role_id" => 'required',
//             "password" => 'nullable|min:6|max:15',             
//         ]; 
//         $validator = Validator::make($request->all(),$rules);
//         if ($validator->fails()) {
//             $errors = $validator->errors()->all();
//             $response=array();
//             $response["status"]=0;
//             $response["msg"]=$errors[0];
//             return response()->json($response);// response as json
//         }
//         $acc_id = $id;

//         //Check Validation
//         $mobile = trim($request->mobile);
//         $account_s = DB::select(DB::raw("select * from `admins` where `id` <> $acc_id and `mobile` = '$mobile';"));
//         $r_count = count($account_s);
//         if($r_count == 1){
//             $response=['status'=>0,'msg'=>'Mobile No. Already Exists'];
//             return response()->json($response);
//             // return redirect()->back()->with(['class'=>'error','message'=>'Mobile No. Already Exists']);
//         }
//         $account_s = DB::select(DB::raw("update `admins` set `first_name` = '$request->first_name', `last_name` = '$request->last_name', `role_id` = $request->role_id, `mobile` = '$mobile' where `id` = $acc_id;"));

//         $bcrypt_password = bcrypt($request['password']);
//         if ($request['password']!=null) {
//             $account_s = DB::select(DB::raw("update `admins` set `password` = '$bcrypt_password' where `id` = $acc_id;"));
//         } 
        
//         return redirect()->route('admin.account.list')->with(['message'=>'Account Updated Successfully.','class'=>'Record Updated Successfully']);
//     }

//     Public function destroy($id){
//         $acc_id = Crypt::decrypt($id);
//         $account = DB::select(DB::raw("Delete from `admins` where `id` = $acc_id;"));
//         return redirect()->back()->with(['message'=>'Account Deleted Successfully','class'=>'success']);
//     }














    





// //---------------Default Role Permission -------------
//     public function defaultRolePermission()
//     {
//         try {
//             $admin=Auth::guard('admin')->user();
//             $role_id = $admin->role_id;
//             $roles =DB::select(DB::raw("select `id`, `name` from `roles` where `id`  >= $role_id Order By `name`;"));
//             return view('admin.account.rolepermission',compact('roles'));
//         } catch (Exception $e) {}
//     }

//     Public function roleMenuTable(Request $request)
//     {
//         try {
//             $id = $request->id;
//             $link_option = 0;   //--0-Side Bar Menu, 1--Quick Links
//             $menus =DB::select(DB::raw("select `id`, `name` from `minu_types` order by `sorting_id`;"));
//             $data= view('admin.account.roleMenuTable',compact('menus', 'id', 'link_option'))->render(); 
//             return response($data);
//         } catch (Exception $e) {}
//     }

//     public function roleMenuStore(Request $request)
//     {
//         $rules=[
//            'sub_menu' => 'required|max:1000',             
//            'role' => 'required|max:199',  
//         ]; 
//         $validator = Validator::make($request->all(),$rules);
//         if ($validator->fails()) {
//             $errors = $validator->errors()->all();
//             $response=array();
//             $response["status"]=0;
//             $response["msg"]=$errors[0];
//             return response()->json($response);// response as json
//         } 

//         try {
//             $sub_menu= implode(',',$request->sub_menu); 
//             DB::select(DB::raw("call `up_set_default_role_permission` ($request->role, '$sub_menu')"));
//             $response['msg'] = 'Permission Saved Successfully';
//             $response['status'] = 1;
//             return response()->json($response);
//         } catch (Exception $e) {}  
//     }

// //-----------------Role Quick Menu ---------------------------------         
//     public function roleQuickView()
//     {
//         try {
//             $admin=Auth::guard('admin')->user();
//             $role_id = $admin->role_id;
//             $roles =DB::select(DB::raw("select `id`, `name` from `roles` where `id`  >= $role_id Order By `name`;"));
//             return view('admin.account.role_quick_view',compact('roles'));
//         } catch (Exception $e) {}
//     } 

//     Public function defultRoleQuickMenuShow(Request $request)
//     {
//         try {
//             $id = $request->id;
//             $link_option = 1;   //--0-Side Bar Menu, 1--Quick Links
//             $menus =DB::select(DB::raw("select `id`, `name` from `minu_types` order by `sorting_id`;"));
//             $data= view('admin.account.roleMenuTable',compact('menus', 'id', 'link_option'))->render(); 
//             return response($data);
//         } catch (Exception $e) {}
//     }

//     public function defaultRoleQuickStore(Request $request){  
//         $rules=[
//             'sub_menu' => 'required|max:1000',             
//             'role' => 'required|max:199',  
//         ]; 
        
//         $validator = Validator::make($request->all(),$rules);
//         if ($validator->fails()) {
//             $errors = $validator->errors()->all();
//             $response=array();
//             $response["status"]=0;
//             $response["msg"]=$errors[0];
//             return response()->json($response);// response as json
//         }  

//         try {
//             $sub_menu= implode(',',$request->sub_menu); 
//             DB::select(DB::raw("call `up_set_default_role_quick_permission` ($request->role, '$sub_menu')")); 
//             $response['msg'] = 'Quick Links Saved Successfully';
//             $response['status'] = 1;
//             return response()->json($response);  
//         } catch (Exception $e) {}
//     }





//     // public function listUserGenerate(Request $request){
//     //     $admin=Auth::guard('admin')->user(); 
//     //     $user_id = $admin->id;
//     //     $accounts = DB::select(DB::raw("select `a`.`id`, `a`.`first_name`, `a`.`last_name`, `a`.`email`, `a`.`mobile`, `a`.`status`, `r`.`name`
//     //          from `admins` `a`Inner Join `roles` `r` on `a`.`role_id` = `r`.`id` where `a`.`created_by` = $user_id Order By `a`.`first_name`;"));  
//     //     $pdf=PDF::setOptions([
//     //         'logOutputFile' => storage_path('logs/log.htm'),
//     //         'tempDir' => storage_path('logs/')
//     //     ])
//     //     ->loadView('admin.account.user_list_pdf_generate',compact('accounts'));
//     //     return $pdf->stream('user_list.pdf');
//     // } 
//     //--------------------End------------------------





    






// //      Public function rstatus(Admin $account){
        
// //         $data = ($account->r_status == 1)?['r_status' => 0]:['r_status' => 1 ]; 
// //         $account->r_status = $data['r_status'];
// //         if( $account->save()){
// //             return redirect()->back()->with(['class'=>'success','message'=>'status change  successfully ...']);   
// //         }
// //         else{
// //             return response()->json(['status'=>'error','message'=>'Whoops, looks like something went wrong ! Try again ...']);
// //         }
// //     }

// //     Public function wstatus(Admin $account){
        
// //         $data = ($account->w_status == 1)?['r_status' => 0]:['r_status' => 1 ]; 
// //         $account->w_status = $data['r_status'];
// //         if( $account->save()){
// //             return redirect()->back()->with(['class'=>'success','message'=>'status change  successfully ...']);   
// //         }
// //         else{
// //             return response()->json(['status'=>'error','message'=>'Whoops, looks like something went wrong ! Try again ...']);
// //         }
// //     }

// //     Public function dstatus(Admin $account){
        
// //         $data = ($account->d_status == 1)?['r_status' => 0]:['r_status' => 1 ]; 
// //         $account->d_status = $data['r_status'];
// //         if( $account->save()){
// //             return redirect()->back()->with(['class'=>'success','message'=>'status change  successfully ...']);   
// //         }
// //         else{
// //             return response()->json(['status'=>'error','message'=>'Whoops, looks like something went wrong ! Try again ...']);
// //         }
// //     }

// //     Public function minu(Request $request, Admin $account){
// //         $roles = Role::all();
// //         $minus = Minu::where('admin_id',$account->id)->get();  
// //         return view('admin.account.minu',compact('account','roles','minus')); 
// //     }

// //     Public function access(Request $request, Admin $account){
// //      $admin=Auth::guard('admin')->user(); 
// //      $menus = MinuType::all();
// //      $users = DB::select(DB::raw("select `id`, `first_name`, `last_name`, `email`, `mobile` from `admins`where `status` = 1 and `role_id` >= (Select `role_id` from `admins` where `id` =$admin->id)Order By `first_name`")); 
// //         return view('admin.account.access',compact('menus','users')); 
// //     } 

// //     Public function accessHotMenu(Request $request, Admin $account){
// //     $admin=Auth::guard('admin')->user();    
// //     $menus = MinuType::all();
// //     $users = DB::select(DB::raw("select `id`, `first_name`, `last_name`, `email`, `mobile` from `admins`where `status` = 1 and `role_id` >= (Select `role_id` from `admins` where `id` =$admin->id)Order By `first_name`")); 
       
// //         return view('admin.account.accessHotMenu',compact('menus','users')); 
// //     } 
// //     Public function accessHotMenuShow(Request $request){  
// //       $id = $request->id;    
// //       $usersmenusType= Minu::where('admin_id',$id)->where('status',1)->get(['sub_menu_id']);
// //       $menusType = Minu::where('admin_id',$id)->where('status',1)->get(['minu_id']);
// //       $menus = MinuType::whereIn('id',$menusType)->get();  
// //       $subMenus = SubMenu::whereIn('id',$usersmenusType)->where('status',1)->get();
// //       $usersmenus = array_pluck(HotMenu::where('admin_id',$id)->where('status',1)->get(['sub_menu_id'])->toArray(), 'sub_menu_id'); 
// //       $data= view('admin.account.hotmenuTable',compact('menus','subMenus','id','usersmenus'))->render(); 
// //       return response($data);
// //     }  

// //     Public function menuTable(Request $request){

// //                 $id = $request->id;
// //             $menus = MinuType::all();
// //             $subMenus = SubMenu::all();
// //            $usersmenus = array_pluck(Minu::where('admin_id',$id)->where('status',1)->get(['sub_menu_id'])->toArray(), 'sub_menu_id'); 
// //         $data= view('admin.account.menuTable',compact('menus','subMenus','usersmenus','id'))->render(); 
// //         return response($data);
// //     }
// //     public function defaultUserMenuAssignReport($id)
// //     {
// //      $id=Crypt::decrypt($id); 
// //      $previousRoute= app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName();
// //      if ($previousRoute=='admin.account.access') {
// //          $usersmenus = array_pluck(Minu::where('admin_id',$id)->where('status',1)->get(['sub_menu_id'])->toArray(), 'sub_menu_id'); 
// //          $menus = MinuType::all();
// //          $subMenus = SubMenu::all(); 
// //      }elseif ($previousRoute=='admin.account.access.hotmenu'){ 
// //       $usersmenusType= Minu::where('admin_id',$id)->where('status',1)->get(['sub_menu_id']);
// //       $menusType = Minu::where('admin_id',$id)->where('status',1)->get(['minu_id']);
// //       $menus = MinuType::whereIn('id',$menusType)->get();  
// //       $subMenus = SubMenu::whereIn('id',$usersmenusType)->where('status',1)->get();
// //       $usersmenus = array_pluck(HotMenu::where('admin_id',$id)->where('status',1)->get(['sub_menu_id'])->toArray(), 'sub_menu_id');
// //      }
      
// //      $pdf = PDF::setOptions([
// //             'logOutputFile' => storage_path('logs/log.htm'),
// //             'tempDir' => storage_path('logs/')
// //         ])
// //         ->loadView('admin.account.report.user_menu_assign_repot',compact('menus','subMenus','usersmenus','id'));
// //         return $pdf->stream('menu_report.pdf');
// //     }





     












    

// //     public function ClassUserAssignReportGenerate($user_id)
// //     {
// //        $usersName = Admin::find($user_id);
// //        $userClassTypes = UserClassType::where('admin_id',$user_id)->where('status',1)->orderBy('class_id','ASC')->orderBy('section_id','ASC')->get();
// //         $pdf=PDF::setOptions([
// //             'logOutputFile' => storage_path('logs/log.htm'),
// //             'tempDir' => storage_path('logs/')
// //         ])
// //         ->loadView('admin.account.report.class_assign_pdf',compact('userClassTypes','usersName'));
// //         return $pdf->stream('academicYear.pdf');
// //     }

// //     // User access Store
// //     Public function accessStore(Request $request){
 
// //             $rules=[
// //             'sub_menu' => 'required|max:1000',             
// //             'user' => 'required|max:1000',  
// //             ]; 
// //             $validator = Validator::make($request->all(),$rules);
// //             if ($validator->fails()) {
// //                 $errors = $validator->errors()->all();
// //                 $response=array();
// //                 $response["status"]=0;
// //                 $response["msg"]=$errors[0];
// //                 return response()->json($response);// response as json
// //             }     
// //         $menuId= implode(',',$request->sub_menu); 
// //         DB::select(DB::raw("call up_setuserpermission ($request->user, '$menuId')")); 
// //         $response['msg'] = 'Access Save Successfully';
// //         $response['status'] = 1;
// //         return response()->json($response);  

        
        
// //     }
// //     // User access hot menu Store
// //     Public function accessHotMenuStore(Request $request){

// //             $rules=[
// //             'sub_menu' => 'required|max:1000',             
// //             'user' => 'required|max:199',  
// //             ]; 
// //             $validator = Validator::make($request->all(),$rules);
// //             if ($validator->fails()) {
// //                 $errors = $validator->errors()->all();
// //                 $response=array();
// //                 $response["status"]=0;
// //                 $response["msg"]=$errors[0];
// //                 return response()->json($response);// response as json
// //             } 
// //             $menuId= implode(',',$request->sub_menu); 
// //             DB::select(DB::raw("call up_setuserquickpermission ($request->user, '$menuId')")); 
            
// //           $response['msg'] = 'Access Save Successfully';
// //            $response['status'] = 1;
// //            return response()->json($response);  

        
        
// //     }



    


 
    

//     public function resetPassWordChange(Request $request)
//     {  
//         if ($request->new_pass!=$request->con_pass) {
//             $response=['status'=>0,'msg'=>'Password Not Match'];
//             return response()->json($response);
//         }

//         $resetPassWordChange=bcrypt($request['new_pass']);
//         $reset_user_id = $request->email;
//         $rs_update = DB::select(DB::raw("update `admins` set `password` = '$resetPassWordChange' where `id` = '$reset_user_id' limit 1;"));

//         // $accounts=Admin::find($request->email); 
//         // $accounts->password=$resetPassWordChange;
//         // $accounts->save(); 
//         $response=['status'=>1,'msg'=>'Password Change Successfully'];
//         return response()->json($response); 
//     }
// //     public function menuOrdering()
// //     {
// //       $menuTypes=MinuType::orderBy('sorting_id','ASC')->get();
// //       return view('admin.account.menu_sorting_order',compact('menuTypes'));
// //     }

// //     public function menuOrderingStore(Request $request)
// //         {  
           
// //           $MinuTypes = MinuType::orderBy('sorting_id', 'ASC')->get();
// //                 $id = Input::get('id');
// //                 $sorting = Input::get('sorting');
// //                 foreach ($MinuTypes as $item) {
// //                     return MinuType::where('id', '=', $id)->update(array('sorting_id' => $sorting));
// //                 } 
// //         }
// //    public function subMenuOrderingStore(Request $request)
// //    {  

// //        $MinuTypes = SubMenu::orderBy('sorting_id', 'ASC')->get();
// //        $id = Input::get('id');
// //        $sorting = Input::get('sorting');
// //        foreach ($MinuTypes as $item) {
// //             SubMenu::where('id', '=', $id)->update(array('sorting_id' => $sorting));
// //        } 
      
// //        $response=array();
// //        $response['msg'] = 'Save Successfully';
// //        $response['status'] = 1;
// //        return response()->json($response); 
// //    }     
// //   public function menuFilter(Request $request,$id)
// //   {
     
// //     $submenus=SubMenu::where('menu_type_id',$id)->orderBy('sorting_id', 'ASC')->get();
// //      return view('admin.account.sub_menu_ordering',compact('submenus'));

// //   } 
// //   public function menuReport(Request $request)
// //   {
// //      $optradio=$request->optradio;
// //      $menus = MinuType::all();
// //      $subMenus=SubMenu::all();
// //      $pdf=PDF::setOptions([
// //             'logOutputFile' => storage_path('logs/log.htm'),
// //             'tempDir' => storage_path('logs/')
// //         ])
// //         ->loadView('admin.account.report.menu_order_report',compact('menus','subMenus','optradio'));
// //         return $pdf->stream('menu_report.pdf');
    

// //   } 
// //   public function defaultUserRolrReportGenerate(Request $request,$id)
// //   {
// //      $id=Crypt::decrypt($id);  
// //      $previousRoute= app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName();
// //      if ($previousRoute=='admin.account.role') {
// //          $datas  = DefaultRoleMenu::where('role_id',$id)->where('status',1)->pluck('sub_menu_id')->toArray();
// //          if ($request->optradio=='selected') {
// //          $subMenus = SubMenu::whereIn('id',$datas)->get();
// //          $menuTypeArrId = SubMenu::whereIn('id',$datas)->pluck('menu_type_id')->toArray();
// //          $menus = MinuType::whereIn('id',$menuTypeArrId)->get();  
// //          }elseif($request->optradio=='all'){
// //           $menus = MinuType::all();
// //           $subMenus = SubMenu::all();      
// //          }
// //      }elseif($previousRoute=='admin.roleAccess.quick.view'){
// //              $datas  = DefaultRoleQuickMenu::where('role_id',$id)->where('status',1)->pluck('sub_menu_id')->toArray();
// //              if ($request->optradio=='selected') {
// //              $subMenus = SubMenu::whereIn('id',$datas)->get();
// //              $menuTypeArrId = SubMenu::whereIn('id',$datas)->pluck('menu_type_id')->toArray();
// //              $menus = MinuType::whereIn('id',$menuTypeArrId)->get();  
// //              }elseif($request->optradio=='all'){
// //               $subMenuArrId  = DefaultRoleMenu::where('role_id',$id)->where('status',1)->pluck('sub_menu_id')->toArray();
// //               $menuTypeArrId = Minu::whereIn('sub_menu_id',$subMenuArrId)->pluck('minu_id')->toArray();
// //               $subMenus = SubMenu::whereIn('id',$subMenuArrId)->get();
// //               $menus = MinuType::whereIn('id',$menuTypeArrId)->get();

// //              // $menus = MinuType::all();
// //              // $subMenus = SubMenu::all();      
// //              }
// //      }
// //      $roles = Role::find($id);
// //      $pdf = PDF::setOptions([
// //             'logOutputFile' => storage_path('logs/log.htm'),
// //             'tempDir' => storage_path('logs/')
// //         ])
// //         ->loadView('admin.account.report.result',compact('menus','subMenus','roles','datas','id'));
// //         return $pdf->stream('menu_report.pdf');
    
// //   }


    
    

}
