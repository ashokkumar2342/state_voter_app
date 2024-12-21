<?php

namespace App\Http\Controllers\Admin; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Helper\MyFuncs;
use App\Helper\SelectBox;

class ClearVotersController extends Controller
{   
    protected $e_controller = "ClearVotersController";

    public function index()
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(43);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $rs_district = SelectBox::get_district_access_list_v1();
            return view('admin.master.ClearVoters.index',compact('rs_district'));
        } catch (\Exception $e) {
            $e_method = "index";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function table(Request $request)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(43);
            if(!$permission_flag){
                return view('admin.common.error');
            }
            
            $village_id = intval(Crypt::decrypt($request->id));
            $permission_flag = MyFuncs::check_village_access($village_id);
            if($permission_flag == 0){
                $village_id = 0;
            }
            
            $rs_result = DB::select(DB::raw("SELECT `pb`.`booth_no`, `wv`.`ward_no`, `vtd`.`total_votes`, `pb`.`id` as `pb_booth_id`, `wv`.`id` as `wv_ward_id` from `ward_villages` `wv` inner join (select distinct `ward_id`, `booth_id`, count(*) as `total_votes` from `voters` where `village_id` = $village_id and `status` <> 2 group by `ward_id`, `booth_id`) `vtd` on `vtd`.`ward_id` = `wv`.`id` inner join `polling_booths` `pb` on `pb`.`id` = `vtd`.`booth_id` order by `pb`.`booth_no`, `wv`.`ward_no`;"));
            return view('admin.master.ClearVoters.table',compact('rs_result'));            
        } catch (\Exception $e) {
            $e_method = "table";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function clear($booth_ward_id)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(43);
            if(!$permission_flag){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }
            
            $val_input = explode(":", Crypt::decrypt($booth_ward_id));
            $booth_id = intval($val_input[0]);
            $ward_id = intval($val_input[1]);
            $user_id = MyFuncs::getUserId();
            $from_ip = MyFuncs::getIp();

            $village_id = 0;
            $rs_fetch = DB::select(DB::raw("SELECT `wv`.`village_id` from `ward_villages` `wv` where `wv`.`id` = $ward_id limit 1;"));
            if (count($rs_fetch) > 0) {
                $village_id = $rs_fetch[0]->village_id;
            }            
            $permission_flag = MyFuncs::check_village_access($village_id);
            if($permission_flag == 0){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }

            $rs_clear = DB::select(DB::raw("call `up_clear_booth_part_voters`($user_id, $booth_id, $ward_id, '$from_ip');"));
            
            $response=['status'=>$rs_clear[0]->save_status,'msg'=>$rs_clear[0]->Save_Result];
            return response()->json($response);
                        
        } catch (\Exception $e) {
            $e_method = "clear";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function shift($booth_ward_id)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(43);
            if(!$permission_flag){
                return view('admin.common.error_popup');
            }
            
            $val_input = explode(":", Crypt::decrypt($booth_ward_id));
            $booth_id = intval($val_input[0]);
            $ward_id = intval($val_input[1]);

            $village_id = 0;
            $ward_no = "";
            $rs_fetch = DB::select(DB::raw("SELECT `wv`.`village_id`, `wv`.`ward_no` from `ward_villages` `wv` where `wv`.`id` = $ward_id limit 1;"));
            if (count($rs_fetch) > 0) {
                $village_id = $rs_fetch[0]->village_id;
                $ward_no = $rs_fetch[0]->ward_no;
            }            
            $permission_flag = MyFuncs::check_village_access($village_id);
            if($permission_flag == 0){
                return view('admin.common.error_popup');
            }

            $booth_no = "";
            $rs_fetch = DB::select(DB::raw("SELECT `booth_no` from `polling_booths` where `id` = $booth_id limit 1;"));
            if (count($rs_fetch) > 0) {
                $booth_no = $rs_fetch[0]->booth_no;
            }            
            
            $assemblyParts = DB::select(DB::raw("SELECT `ap`.`id`, concat(`ac`.`code`, ' - ', `ap`.`part_no`) as `opt_text`, `vt`.`t_voters` from `assembly_parts` `ap` inner join (select `assembly_part_id`, count(*) as `t_voters` from `voters` where `booth_id` = $booth_id group by `assembly_part_id`) `vt` on `vt`.`assembly_part_id` = `ap`.`id` inner join `assemblys` `ac` on `ac`.`id` = `ap`.`assembly_id` order by `ac`.`code`, `ap`.`part_no`;"));
            $WardVillages = DB::select(DB::raw("call up_fetch_ward_village_access ($village_id, 0);"));
            
            return view('admin.master.ClearVoters.shift_popup',compact('assemblyParts', 'WardVillages', 'ward_no', 'booth_no', 'booth_ward_id'));            
        } catch (\Exception $e) {
            $e_method = "shift";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function shiftStore(Request $request)
    {
        try {
            $permission_flag = MyFuncs::isPermission_route(43);
            if(!$permission_flag){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }

            $rules=[
                'booth_ward_id' => 'required', 
                'assembly_part' => 'required', 
                'ward' => 'required', 
                'booth' => 'required', 
            ];
            $customMessages = [
                'booth_ward_id.required'=> 'Something Went Wrong',
                'assembly_part.required'=> 'Please Select From Assembly/Part',
                'ward.required'=> 'Please Select To Ward No.',
                'booth.required'=> 'Please Select To Booth No.',
            ];
            $validator = Validator::make($request->all(),$rules, $customMessages);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $response=array();
                $response["status"]=0;
                $response["msg"]=$errors[0];
                return response()->json($response);// response as json
            }
            
            $val_input = explode(":", Crypt::decrypt($request->booth_ward_id));
            $from_booth_id = intval($val_input[0]);
            $from_ward_id = intval($val_input[1]);
            $user_id = MyFuncs::getUserId();
            $from_ip = MyFuncs::getIp();

            $from_ac_ap_id = intval(Crypt::decrypt($request->assembly_part));
            $to_ward_id = intval(Crypt::decrypt($request->ward));
            $to_booth_id = intval(Crypt::decrypt($request->booth));

            $village_id = 0;
            $rs_fetch = DB::select(DB::raw("SELECT `wv`.`village_id` from `ward_villages` `wv` where `wv`.`id` = $from_ward_id limit 1;"));
            if (count($rs_fetch) > 0) {
                $village_id = $rs_fetch[0]->village_id;
            }            
            $permission_flag = MyFuncs::check_village_access($village_id);
            if($permission_flag == 0){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }

            $village_id = 0;
            $rs_fetch = DB::select(DB::raw("SELECT `wv`.`village_id` from `ward_villages` `wv` where `wv`.`id` = $to_ward_id limit 1;"));
            if (count($rs_fetch) > 0) {
                $village_id = $rs_fetch[0]->village_id;
            }            
            $permission_flag = MyFuncs::check_village_access($village_id);
            if($permission_flag == 0){
                $response=['status'=>0,'msg'=>'Something Went Wrong'];
                return response()->json($response);
            }

            $query = "call `up_shift_booth_part_voters`($user_id, $from_booth_id, $from_ward_id, $from_ac_ap_id, $to_ward_id, $to_booth_id, '$from_ip');";
            // return $query;

            $rs_shift = DB::select(DB::raw("$query"));
            
            $response=['status'=>$rs_shift[0]->save_status,'msg'=>$rs_shift[0]->Save_Result];
            return response()->json($response);
                        
        } catch (\Exception $e) {
            $e_method = "shiftStore";
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
}
