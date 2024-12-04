<?php

namespace App\Http\Controllers\Admin;

// use App\Admin;
// use App\AdminOtp;
use App\Http\Controllers\Controller;
// use App\Model\BlocksMc;
// use App\Model\DefaultRoleMenu;
// use App\Model\DefaultRoleQuickMenu;
// use App\Model\District;
// use App\Model\HotMenu;
// use App\Model\Minu;
// use App\Model\MinuType;
// use App\Model\Role;
// use App\Model\State;
// use App\Model\SubMenu;
// use App\Model\UserBlockAssign;
// use App\Model\UserDistrictAssign;
// use App\Model\UserVillageAssign;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Mail;
use PDF;
use Symfony\Component\HttpKernel\DataCollector\collect;
class AccountController extends Controller
{
    Public function index(){
        $admin=Auth::guard('admin')->user(); 
        $user_id = $admin->id;
        $accounts = DB::select(DB::raw("select `a`.`id`, `a`.`first_name`, `a`.`last_name`, `a`.`email`, `a`.`mobile`, `a`.`status`, `r`.`name`
             from `admins` `a`Inner Join `roles` `r` on `a`.`role_id` = `r`.`id` where `a`.`created_by` = $user_id Order By `a`.`first_name`;")); 
     return view('admin.account.list',compact('accounts'));
    }

    Public function form(Request $request){
        $admin=Auth::guard('admin')->user();       
        $user_role = $admin->role_id;
        $roles =DB::select(DB::raw("select `id`, `name` from `roles` where `id`  > $user_role Order By `name`;"));
        return view('admin.account.form',compact('roles'));
    }    

    Public function store(Request $request){ 
        $rules=[
            'first_name' => 'required|string|min:3|max:50',             
            'email' => 'required|email|unique:admins',
            "mobile" => 'required|unique:admins|numeric|digits:10',
            "role_id" => 'required',
            "password" => 'required|min:6|max:15', 
        ];

        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $response=array();
            $response["status"]=0;
            $response["msg"]=$errors[0];
            return response()->json($response);// response as json
        }
        
        $admin=Auth::guard('admin')->user(); 
        $user_id = $admin->id;
        $en_password = bcrypt($request['password']);
        DB::select(DB::raw("Insert Into `admins` (`first_name`, `last_name`, `email`, `mobile`, `password`, `password_plain`, `role_id`, `created_by`) Values ('$request->first_name', '$request->last_name', '$request->email', '$request->mobile', '$en_password','',$request->role_id, $user_id);"));

        $response=['status'=>1,'msg'=>'Account Created Successfully'];
        return response()->json($response);   
    }

    Public function status($id){
        $acc_id = Crypt::decrypt($id);
        DB::select(DB::raw("call `up_toggle_user_status`($acc_id);"));
        return redirect()->back()->with(['class'=>'success','message'=>'status change  successfully ...']);
    }

    Public function edit($id){
        $acc_id = Crypt::decrypt($id);
        $admin=Auth::guard('admin')->user();       
        $user_role = $admin->role_id;
        $roles =DB::select(DB::raw("select `id`, `name` from `roles` where `id`  > $user_role Order By `name`;"));
        
        $account = DB::select(DB::raw("select * from `admins` where `id` = $acc_id;"));
        return view('admin.account.edit',compact('account','roles')); 
    }

    Public function update(Request $request, $id){

        $rules=[
            'first_name' => 'required|string|min:3|max:50',
            "mobile" => 'required|numeric|digits:10',
            "role_id" => 'required',
            "password" => 'nullable|min:6|max:15',             
        ]; 
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $response=array();
            $response["status"]=0;
            $response["msg"]=$errors[0];
            return response()->json($response);// response as json
        }
        $acc_id = $id;

        //Check Validation
        $mobile = trim($request->mobile);
        $account_s = DB::select(DB::raw("select * from `admins` where `id` <> $acc_id and `mobile` = '$mobile';"));
        $r_count = count($account_s);
        if($r_count == 1){
            $response=['status'=>0,'msg'=>'Mobile No. Already Exists'];
            return response()->json($response);
            // return redirect()->back()->with(['class'=>'error','message'=>'Mobile No. Already Exists']);
        }
        $account_s = DB::select(DB::raw("update `admins` set `first_name` = '$request->first_name', `last_name` = '$request->last_name', `role_id` = $request->role_id, `mobile` = '$mobile' where `id` = $acc_id;"));

        $bcrypt_password = bcrypt($request['password']);
        if ($request['password']!=null) {
            $account_s = DB::select(DB::raw("update `admins` set `password` = '$bcrypt_password' where `id` = $acc_id;"));
        } 
        
        return redirect()->route('admin.account.list')->with(['message'=>'Account Updated Successfully.','class'=>'Record Updated Successfully']);
    }

    Public function destroy($id){
        $acc_id = Crypt::decrypt($id);
        $account = DB::select(DB::raw("Delete from `admins` where `id` = $acc_id;"));
        return redirect()->back()->with(['message'=>'Account Deleted Successfully','class'=>'success']);
    }

    Public function DistrictsAssign(){
        $admin=Auth::guard('admin')->user(); 
        $users=DB::select(DB::raw("select `id`, `first_name`, `last_name`, `email`, `mobile` from `admins`where `status` = 1 and `role_id` = 2 and `created_by` = $admin->id Order By `first_name`")); 
        return view('admin.account.assign.district.index',compact('users'));
       
    }

    Public function StateDistrictsSelect(Request $request){  
        $States = DB::select(DB::raw("select * from `states` Order By `name_e`"));   
        
        $DistrictBlockAssigns = DB::select(DB::raw("select `uda`.`id`, `dis`.`name_e` from `user_district_assigns` `uda` inner join `districts` `dis` on `dis`.`id` = `uda`.`district_id` where `uda`.`status` = 1 and `uda`.`user_id` = $request->id;"));
        
        $data= view('admin.account.assign.district.select_box',compact('DistrictBlockAssigns','States'))->render(); 
        return response($data);
    }

    Public function DistrictsAssignStore(Request $request){    
        $rules=[
            'district' => 'required', 
            'user' => 'required',  
        ]; 
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $response=array();
            $response["status"]=0;
            $response["msg"]=$errors[0];
            return response()->json($response);// response as json
        }
        $rs_save = DB::select(DB::raw("call `up_save_assign_district` ($request->user, $request->district);"));  
        $response['msg'] = 'District Assigned Successfully';
        $response['status'] = 1;
        return response()->json($response);  
    }

    public function DistrictsAssignDelete($id)
    {
        try {
            $assigned_id = Crypt::decrypt($id);
            $rs_delete = DB::select(DB::raw("delete from `user_district_assigns` where `id` = $assigned_id;"));
            $response['msg'] = 'District Removed Successfully';
            $response['status'] = 1;
            return response()->json($response);   
        } catch (Exception $e) {}
    }

//     //-----------block-assign----------------------------------//

    Public function BlockAssign(){
        try {
            $admin=Auth::guard('admin')->user(); 
            $users=DB::select(DB::raw("select `id`, `first_name`, `last_name`, `email`, `mobile` from `admins`where `status` = 1 and `role_id` = 3 and `created_by` = $admin->id Order By `first_name`")); 
            return view('admin.account.assign.block.index',compact('users'));
        } catch (Exception $e) {}
    }

    Public function DistrictBlockAssign(Request $request){ 
        try {
            $States = DB::select(DB::raw("select * from `states` Order By `name_e`"));           
            $DistrictBlockAssigns = DB::select(DB::raw("select `uda`.`id`, `dis`.`name_e` from `user_block_assigns` `uda` inner join `blocks_mcs` `dis` on `dis`.`id` = `uda`.`block_id` where `uda`.`status` = 1 and `uda`.`user_id` = $request->id;"));

            $data= view('admin.account.assign.block.select_box',compact('DistrictBlockAssigns','States'))->render(); 
            return response($data);
        } catch (Exception $e) {}
    }

    Public function DistrictBlockAssignStore(Request $request){     
        $rules=[
            'district' => 'required', 
            'block' => 'required', 
            'user' => 'required',  
        ]; 
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $response=array();
            $response["status"]=0;
            $response["msg"]=$errors[0];
            return response()->json($response);// response as json
        }
        
        try {
            $rs_save = DB::select(DB::raw("call `up_save_assign_block` ($request->user, $request->district, $request->block);"));
            $response['msg'] = 'Block Assigned Successfully';
            $response['status'] = 1;
            return response()->json($response);  
        } catch (Exception $e) {}
    }

    public function DistrictBlockAssignDelete($id)
    {
        try {
            $assigned_id = Crypt::decrypt($id);
            $rs_delete = DB::select(DB::raw("delete from `user_block_assigns` where `id` = $assigned_id;"));
            $response['msg'] = 'Block Removed Successfully';
            $response['status'] = 1;
            return response()->json($response);   
         } catch (Exception $e) {}
    }

// ///------village-Assign-----------------------------------
    Public function VillageAssign(){
        try {
            $admin=Auth::guard('admin')->user(); 
            $users=DB::select(DB::raw("select `id`, `first_name`, `last_name`, `email`, `mobile` from `admins`where `status` = 1 and `role_id` = 4 and `created_by` = $admin->id Order By `first_name`")); 
            return view('admin.account.assign.village.index',compact('users'));
        } catch (Exception $e) {}  
    }

    Public function DistrictBlockVillageAssign(Request $request){ 
        try {
            $States = DB::select(DB::raw("select * from `states` Order By `name_e`"));           
            $DistrictBlockAssigns = DB::select(DB::raw("select `uda`.`id`, `dis`.`name_e` as `block_name`, `vil`.`name_e` as `vil_name` from `user_village_assigns` `uda` inner join `blocks_mcs` `dis` on `dis`.`id` = `uda`.`block_id` inner join `villages` `vil` on `vil`.`id` = `uda`.`village_id` where `uda`.`status` = 1 and `uda`.`user_id` = $request->id;"));
            $data= view('admin.account.assign.village.select_box',compact('DistrictBlockAssigns','States'))->render(); 
            return response($data);
        } catch (Exception $e) {}

    }

    Public function DistrictBlockVillageAssignStore(Request $request){   
        $rules=[
            'district' => 'required', 
            'block' => 'required', 
            'village' => 'required', 
            'user' => 'required',  
        ]; 
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $response=array();
            $response["status"]=0;
            $response["msg"]=$errors[0];
            return response()->json($response);// response as json
        }
        try {
            $rs_save = DB::select(DB::raw("call `up_save_assign_village`($request->user, $request->district, $request->block, $request->village);"));
            $response['msg'] = 'Village Assigned Successfully';
            $response['status'] = 1;
            return response()->json($response);
        } catch (Exception $e) {}  
    }

    public function DistrictBlockVillageAssignDelete($id)
    {
        try {
            $assign_id = Crypt::decrypt($id);
            $rs_delete = DB::select(DB::raw("delete from `user_village_assigns` where `id` = $assign_id limit 1;"));
            $response['msg'] = 'Village Removed Successfully';
            $response['status'] = 1;
            return response()->json($response);
        } catch (Exception $e) {}   
    }

//---------------Default Role Permission -------------
    public function defaultRolePermission()
    {
        try {
            $admin=Auth::guard('admin')->user();
            $role_id = $admin->role_id;
            $roles =DB::select(DB::raw("select `id`, `name` from `roles` where `id`  >= $role_id Order By `name`;"));
            return view('admin.account.rolepermission',compact('roles'));
        } catch (Exception $e) {}
    }

    Public function roleMenuTable(Request $request)
    {
        try {
            $id = $request->id;
            $link_option = 0;   //--0-Side Bar Menu, 1--Quick Links
            $menus =DB::select(DB::raw("select `id`, `name` from `minu_types` order by `sorting_id`;"));
            $data= view('admin.account.roleMenuTable',compact('menus', 'id', 'link_option'))->render(); 
            return response($data);
        } catch (Exception $e) {}
    }

    public function roleMenuStore(Request $request)
    {
        $rules=[
           'sub_menu' => 'required|max:1000',             
           'role' => 'required|max:199',  
        ]; 
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $response=array();
            $response["status"]=0;
            $response["msg"]=$errors[0];
            return response()->json($response);// response as json
        } 

        try {
            $sub_menu= implode(',',$request->sub_menu); 
            DB::select(DB::raw("call `up_set_default_role_permission` ($request->role, '$sub_menu')"));
            $response['msg'] = 'Permission Saved Successfully';
            $response['status'] = 1;
            return response()->json($response);
        } catch (Exception $e) {}  
    }

//-----------------Role Quick Menu ---------------------------------         
    public function roleQuickView()
    {
        try {
            $admin=Auth::guard('admin')->user();
            $role_id = $admin->role_id;
            $roles =DB::select(DB::raw("select `id`, `name` from `roles` where `id`  >= $role_id Order By `name`;"));
            return view('admin.account.role_quick_view',compact('roles'));
        } catch (Exception $e) {}
    } 

    Public function defultRoleQuickMenuShow(Request $request)
    {
        try {
            $id = $request->id;
            $link_option = 1;   //--0-Side Bar Menu, 1--Quick Links
            $menus =DB::select(DB::raw("select `id`, `name` from `minu_types` order by `sorting_id`;"));
            $data= view('admin.account.roleMenuTable',compact('menus', 'id', 'link_option'))->render(); 
            return response($data);
        } catch (Exception $e) {}
    }

    public function defaultRoleQuickStore(Request $request){  
        $rules=[
            'sub_menu' => 'required|max:1000',             
            'role' => 'required|max:199',  
        ]; 
        
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $response=array();
            $response["status"]=0;
            $response["msg"]=$errors[0];
            return response()->json($response);// response as json
        }  

        try {
            $sub_menu= implode(',',$request->sub_menu); 
            DB::select(DB::raw("call `up_set_default_role_quick_permission` ($request->role, '$sub_menu')")); 
            $response['msg'] = 'Quick Links Saved Successfully';
            $response['status'] = 1;
            return response()->json($response);  
        } catch (Exception $e) {}
    }
    public function changePassword($value='')
    {
        return view('admin.account.change_password');
    }
    public function changePasswordStore(Request $request)
    { 
        $rules=[
            'oldpassword'=> 'required',
            'password'=> 'required|min:6',
            'passwordconfirmation'=> 'required|min:6|same:password',
        ];
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $response=array();
            $response["status"]=0;
            $response["msg"]=$errors[0];
            return response()->json($response);// response as json
        }        
        $user=Auth::guard('admin')->user();
        $userid = $user->id;              
        
        if(password_verify($request->oldpassword,$user->password)){
            if ($request->oldpassword == $request->password) {
                $response=['status'=>0,'msg'=>'Old Password And New Password Cannot Be Same'];
                return response()->json($response);
            }else{
                $en_password = bcrypt($request['password']); 
                DB::select(DB::raw("update `admins` set `password` = '$en_password' where `id` = $userid limit 1"));
                $response=['status'=>1,'msg'=>'Password Change Successfully'];
                return response()->json($response);// response as json 
            }
        }else{               
            $response=['status'=>0,'msg'=>'Old Password Is Not Correct'];
            return response()->json($response);// response as json
        }        
    }



    // public function listUserGenerate(Request $request){
    //     $admin=Auth::guard('admin')->user(); 
    //     $user_id = $admin->id;
    //     $accounts = DB::select(DB::raw("select `a`.`id`, `a`.`first_name`, `a`.`last_name`, `a`.`email`, `a`.`mobile`, `a`.`status`, `r`.`name`
    //          from `admins` `a`Inner Join `roles` `r` on `a`.`role_id` = `r`.`id` where `a`.`created_by` = $user_id Order By `a`.`first_name`;"));  
    //     $pdf=PDF::setOptions([
    //         'logOutputFile' => storage_path('logs/log.htm'),
    //         'tempDir' => storage_path('logs/')
    //     ])
    //     ->loadView('admin.account.user_list_pdf_generate',compact('accounts'));
    //     return $pdf->stream('user_list.pdf');
    // } 
    //--------------------End------------------------





    






//      Public function rstatus(Admin $account){
        
//         $data = ($account->r_status == 1)?['r_status' => 0]:['r_status' => 1 ]; 
//         $account->r_status = $data['r_status'];
//         if( $account->save()){
//             return redirect()->back()->with(['class'=>'success','message'=>'status change  successfully ...']);   
//         }
//         else{
//             return response()->json(['status'=>'error','message'=>'Whoops, looks like something went wrong ! Try again ...']);
//         }
//     }

//     Public function wstatus(Admin $account){
        
//         $data = ($account->w_status == 1)?['r_status' => 0]:['r_status' => 1 ]; 
//         $account->w_status = $data['r_status'];
//         if( $account->save()){
//             return redirect()->back()->with(['class'=>'success','message'=>'status change  successfully ...']);   
//         }
//         else{
//             return response()->json(['status'=>'error','message'=>'Whoops, looks like something went wrong ! Try again ...']);
//         }
//     }

//     Public function dstatus(Admin $account){
        
//         $data = ($account->d_status == 1)?['r_status' => 0]:['r_status' => 1 ]; 
//         $account->d_status = $data['r_status'];
//         if( $account->save()){
//             return redirect()->back()->with(['class'=>'success','message'=>'status change  successfully ...']);   
//         }
//         else{
//             return response()->json(['status'=>'error','message'=>'Whoops, looks like something went wrong ! Try again ...']);
//         }
//     }

//     Public function minu(Request $request, Admin $account){
//         $roles = Role::all();
//         $minus = Minu::where('admin_id',$account->id)->get();  
//         return view('admin.account.minu',compact('account','roles','minus')); 
//     }

//     Public function access(Request $request, Admin $account){
//      $admin=Auth::guard('admin')->user(); 
//      $menus = MinuType::all();
//      $users = DB::select(DB::raw("select `id`, `first_name`, `last_name`, `email`, `mobile` from `admins`where `status` = 1 and `role_id` >= (Select `role_id` from `admins` where `id` =$admin->id)Order By `first_name`")); 
//         return view('admin.account.access',compact('menus','users')); 
//     } 

//     Public function accessHotMenu(Request $request, Admin $account){
//     $admin=Auth::guard('admin')->user();    
//     $menus = MinuType::all();
//     $users = DB::select(DB::raw("select `id`, `first_name`, `last_name`, `email`, `mobile` from `admins`where `status` = 1 and `role_id` >= (Select `role_id` from `admins` where `id` =$admin->id)Order By `first_name`")); 
       
//         return view('admin.account.accessHotMenu',compact('menus','users')); 
//     } 
//     Public function accessHotMenuShow(Request $request){  
//       $id = $request->id;    
//       $usersmenusType= Minu::where('admin_id',$id)->where('status',1)->get(['sub_menu_id']);
//       $menusType = Minu::where('admin_id',$id)->where('status',1)->get(['minu_id']);
//       $menus = MinuType::whereIn('id',$menusType)->get();  
//       $subMenus = SubMenu::whereIn('id',$usersmenusType)->where('status',1)->get();
//       $usersmenus = array_pluck(HotMenu::where('admin_id',$id)->where('status',1)->get(['sub_menu_id'])->toArray(), 'sub_menu_id'); 
//       $data= view('admin.account.hotmenuTable',compact('menus','subMenus','id','usersmenus'))->render(); 
//       return response($data);
//     }  

//     Public function menuTable(Request $request){

//                 $id = $request->id;
//             $menus = MinuType::all();
//             $subMenus = SubMenu::all();
//            $usersmenus = array_pluck(Minu::where('admin_id',$id)->where('status',1)->get(['sub_menu_id'])->toArray(), 'sub_menu_id'); 
//         $data= view('admin.account.menuTable',compact('menus','subMenus','usersmenus','id'))->render(); 
//         return response($data);
//     }
//     public function defaultUserMenuAssignReport($id)
//     {
//      $id=Crypt::decrypt($id); 
//      $previousRoute= app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName();
//      if ($previousRoute=='admin.account.access') {
//          $usersmenus = array_pluck(Minu::where('admin_id',$id)->where('status',1)->get(['sub_menu_id'])->toArray(), 'sub_menu_id'); 
//          $menus = MinuType::all();
//          $subMenus = SubMenu::all(); 
//      }elseif ($previousRoute=='admin.account.access.hotmenu'){ 
//       $usersmenusType= Minu::where('admin_id',$id)->where('status',1)->get(['sub_menu_id']);
//       $menusType = Minu::where('admin_id',$id)->where('status',1)->get(['minu_id']);
//       $menus = MinuType::whereIn('id',$menusType)->get();  
//       $subMenus = SubMenu::whereIn('id',$usersmenusType)->where('status',1)->get();
//       $usersmenus = array_pluck(HotMenu::where('admin_id',$id)->where('status',1)->get(['sub_menu_id'])->toArray(), 'sub_menu_id');
//      }
      
//      $pdf = PDF::setOptions([
//             'logOutputFile' => storage_path('logs/log.htm'),
//             'tempDir' => storage_path('logs/')
//         ])
//         ->loadView('admin.account.report.user_menu_assign_repot',compact('menus','subMenus','usersmenus','id'));
//         return $pdf->stream('menu_report.pdf');
//     }





     












    

//     public function ClassUserAssignReportGenerate($user_id)
//     {
//        $usersName = Admin::find($user_id);
//        $userClassTypes = UserClassType::where('admin_id',$user_id)->where('status',1)->orderBy('class_id','ASC')->orderBy('section_id','ASC')->get();
//         $pdf=PDF::setOptions([
//             'logOutputFile' => storage_path('logs/log.htm'),
//             'tempDir' => storage_path('logs/')
//         ])
//         ->loadView('admin.account.report.class_assign_pdf',compact('userClassTypes','usersName'));
//         return $pdf->stream('academicYear.pdf');
//     }

//     // User access Store
//     Public function accessStore(Request $request){
 
//             $rules=[
//             'sub_menu' => 'required|max:1000',             
//             'user' => 'required|max:1000',  
//             ]; 
//             $validator = Validator::make($request->all(),$rules);
//             if ($validator->fails()) {
//                 $errors = $validator->errors()->all();
//                 $response=array();
//                 $response["status"]=0;
//                 $response["msg"]=$errors[0];
//                 return response()->json($response);// response as json
//             }     
//         $menuId= implode(',',$request->sub_menu); 
//         DB::select(DB::raw("call up_setuserpermission ($request->user, '$menuId')")); 
//         $response['msg'] = 'Access Save Successfully';
//         $response['status'] = 1;
//         return response()->json($response);  

        
        
//     }
//     // User access hot menu Store
//     Public function accessHotMenuStore(Request $request){

//             $rules=[
//             'sub_menu' => 'required|max:1000',             
//             'user' => 'required|max:199',  
//             ]; 
//             $validator = Validator::make($request->all(),$rules);
//             if ($validator->fails()) {
//                 $errors = $validator->errors()->all();
//                 $response=array();
//                 $response["status"]=0;
//                 $response["msg"]=$errors[0];
//                 return response()->json($response);// response as json
//             } 
//             $menuId= implode(',',$request->sub_menu); 
//             DB::select(DB::raw("call up_setuserquickpermission ($request->user, '$menuId')")); 
            
//           $response['msg'] = 'Access Save Successfully';
//            $response['status'] = 1;
//            return response()->json($response);  

        
        
//     }



    


 
    public function resetPassWord($value='')
    {
       $admins = DB::select(DB::raw("select * from `admins` order by `email`;"));
       return view('admin.account.reset_password',compact('admins'));
    }

    public function resetPassWordChange(Request $request)
    {  
        if ($request->new_pass!=$request->con_pass) {
            $response=['status'=>0,'msg'=>'Password Not Match'];
            return response()->json($response);
        }

        $resetPassWordChange=bcrypt($request['new_pass']);
        $reset_user_id = $request->email;
        $rs_update = DB::select(DB::raw("update `admins` set `password` = '$resetPassWordChange' where `id` = '$reset_user_id' limit 1;"));

        // $accounts=Admin::find($request->email); 
        // $accounts->password=$resetPassWordChange;
        // $accounts->save(); 
        $response=['status'=>1,'msg'=>'Password Change Successfully'];
        return response()->json($response); 
    }
//     public function menuOrdering()
//     {
//       $menuTypes=MinuType::orderBy('sorting_id','ASC')->get();
//       return view('admin.account.menu_sorting_order',compact('menuTypes'));
//     }

//     public function menuOrderingStore(Request $request)
//         {  
           
//           $MinuTypes = MinuType::orderBy('sorting_id', 'ASC')->get();
//                 $id = Input::get('id');
//                 $sorting = Input::get('sorting');
//                 foreach ($MinuTypes as $item) {
//                     return MinuType::where('id', '=', $id)->update(array('sorting_id' => $sorting));
//                 } 
//         }
//    public function subMenuOrderingStore(Request $request)
//    {  

//        $MinuTypes = SubMenu::orderBy('sorting_id', 'ASC')->get();
//        $id = Input::get('id');
//        $sorting = Input::get('sorting');
//        foreach ($MinuTypes as $item) {
//             SubMenu::where('id', '=', $id)->update(array('sorting_id' => $sorting));
//        } 
      
//        $response=array();
//        $response['msg'] = 'Save Successfully';
//        $response['status'] = 1;
//        return response()->json($response); 
//    }     
//   public function menuFilter(Request $request,$id)
//   {
     
//     $submenus=SubMenu::where('menu_type_id',$id)->orderBy('sorting_id', 'ASC')->get();
//      return view('admin.account.sub_menu_ordering',compact('submenus'));

//   } 
//   public function menuReport(Request $request)
//   {
//      $optradio=$request->optradio;
//      $menus = MinuType::all();
//      $subMenus=SubMenu::all();
//      $pdf=PDF::setOptions([
//             'logOutputFile' => storage_path('logs/log.htm'),
//             'tempDir' => storage_path('logs/')
//         ])
//         ->loadView('admin.account.report.menu_order_report',compact('menus','subMenus','optradio'));
//         return $pdf->stream('menu_report.pdf');
    

//   } 
//   public function defaultUserRolrReportGenerate(Request $request,$id)
//   {
//      $id=Crypt::decrypt($id);  
//      $previousRoute= app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName();
//      if ($previousRoute=='admin.account.role') {
//          $datas  = DefaultRoleMenu::where('role_id',$id)->where('status',1)->pluck('sub_menu_id')->toArray();
//          if ($request->optradio=='selected') {
//          $subMenus = SubMenu::whereIn('id',$datas)->get();
//          $menuTypeArrId = SubMenu::whereIn('id',$datas)->pluck('menu_type_id')->toArray();
//          $menus = MinuType::whereIn('id',$menuTypeArrId)->get();  
//          }elseif($request->optradio=='all'){
//           $menus = MinuType::all();
//           $subMenus = SubMenu::all();      
//          }
//      }elseif($previousRoute=='admin.roleAccess.quick.view'){
//              $datas  = DefaultRoleQuickMenu::where('role_id',$id)->where('status',1)->pluck('sub_menu_id')->toArray();
//              if ($request->optradio=='selected') {
//              $subMenus = SubMenu::whereIn('id',$datas)->get();
//              $menuTypeArrId = SubMenu::whereIn('id',$datas)->pluck('menu_type_id')->toArray();
//              $menus = MinuType::whereIn('id',$menuTypeArrId)->get();  
//              }elseif($request->optradio=='all'){
//               $subMenuArrId  = DefaultRoleMenu::where('role_id',$id)->where('status',1)->pluck('sub_menu_id')->toArray();
//               $menuTypeArrId = Minu::whereIn('sub_menu_id',$subMenuArrId)->pluck('minu_id')->toArray();
//               $subMenus = SubMenu::whereIn('id',$subMenuArrId)->get();
//               $menus = MinuType::whereIn('id',$menuTypeArrId)->get();

//              // $menus = MinuType::all();
//              // $subMenus = SubMenu::all();      
//              }
//      }
//      $roles = Role::find($id);
//      $pdf = PDF::setOptions([
//             'logOutputFile' => storage_path('logs/log.htm'),
//             'tempDir' => storage_path('logs/')
//         ])
//         ->loadView('admin.account.report.result',compact('menus','subMenus','roles','datas','id'));
//         return $pdf->stream('menu_report.pdf');
    
//   }


    
    

}
