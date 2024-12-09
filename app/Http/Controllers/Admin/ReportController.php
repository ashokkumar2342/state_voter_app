<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use App\Helper\MyFuncs;
use App\Helper\SelectBox;

class ReportController extends Controller
{
    protected $e_controller = "ReportController";
    public function exception_handler()
    {
        try {
                
        } catch (\Exception $e) {
            $e_method = "imageShowPath";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function master_data_index()
    {
        try {
            $permission_flag = MyFuncs::isPermission_route("29");
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $role_id = MyFuncs::getUserRoleId();
            $report_type_id = 1;
            
            $reportTypes = DB::select(DB::raw("SELECT * from `report_types` where `report_for` = $role_id and `report_type_id` = $report_type_id order by `id`; "));
            return view('admin.report.master_data.master_index',compact('reportTypes'));       
        } catch (\Exception $e) {
            $e_method = "master_data_index";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function misc_rep_index()
    {
        try {
            $permission_flag = MyFuncs::isPermission_route("101");
            if(!$permission_flag){
                return view('admin.common.error');
            }
            $role_id = MyFuncs::getUserRoleId();
            $report_type_id = 2;
            
            $reportTypes = DB::select(DB::raw("SELECT * from `report_types` where `report_for` = $role_id and `report_type_id` = $report_type_id order by `id`; "));
            return view('admin.report.master_data.master_index',compact('reportTypes'));       
        } catch (\Exception $e) {
            $e_method = "misc_rep_index";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    // public function student_info_index()
    // {
    //     try {
    //         $permission_flag = MyFuncs::isPermission_route("263");
    //         if(!$permission_flag){
    //             return view('admin.common.error');
    //         }
    //         $role_id = MyFuncs::getUserRoleId();
    //         $report_type_id = 7;
            
    //         $reportTypes = DB::select(DB::raw("SELECT * from `report_types` where `report_for` = $role_id and `report_type_id` = $report_type_id order by `name`; "));
    //         return view('admin.report.studentInfoIndex',compact('reportTypes'));       
    //     } catch (\Exception $e) {
    //         $e_method = "student_info_index";
    //         return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    //     }
    // }

    // public function attendance_index()
    // {
    //     try {
    //         $permission_flag = MyFuncs::isPermission_route("127");
    //         if(!$permission_flag){
    //             return view('admin.common.error');
    //         }
    //         $role_id = MyFuncs::getUserRoleId();
    //         $report_type_id = 15;
            
    //         $reportTypes = DB::select(DB::raw("SELECT * from `report_types` where `report_for` = $role_id and `report_type_id` = $report_type_id order by `id`; "));
    //         return view('admin.report.attendance.index',compact('reportTypes'));       
    //     } catch (\Exception $e) {
    //         $e_method = "attendance_index";
    //         return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    //     }
    // }

    // public function fee_report_index()
    // {
    //     try {
    //         $permission_flag = MyFuncs::isPermission_route("120");
    //         if(!$permission_flag){
    //             return view('admin.common.error');
    //         }
            
    //         $role_id = MyFuncs::getUserRoleId();
    //         $report_type_id = 12;
            
    //         $reportTypes = DB::select(DB::raw("SELECT * from `report_types` where `report_for` = $role_id and `report_type_id` = $report_type_id order by `name`; "));
    //         return view('admin.report.fee_report_index',compact('reportTypes'));
    //     } catch (\Exception $e) {
    //         $e_method = "fee_report_index";
    //         return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    //     }
    // }

    // public function library_report_index()
    // {
    //     try {
    //         $permission_flag = MyFuncs::isPermission_route("153");
    //         if(!$permission_flag){
    //             return view('admin.common.error');
    //         }
    //         $role_id = MyFuncs::getUserRoleId();
    //         $report_type_id = 17;
            
    //         $reportTypes = DB::select(DB::raw("SELECT * from `report_types` where `report_for` = $role_id and `report_type_id` = $report_type_id order by `id`; "));
    //         return view('admin.report.library.index',compact('reportTypes'));       
    //     } catch (\Exception $e) {
    //         $e_method = "library_report_index";
    //         return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    //     }
    // }

    // public function ut_ct_report_index()
    // {
    //     try {
    //         $permission_flag = MyFuncs::isPermission_route("803");
    //         if(!$permission_flag){
    //             return view('admin.common.error');
    //         }
    //         $role_id = MyFuncs::getUserRoleId();
    //         $report_type_id = 20;
            
    //         $reportTypes = DB::select(DB::raw("SELECT * from `report_types` where `report_for` = $role_id and `report_type_id` = $report_type_id order by `id`; "));
    //         return view('admin.report.ut_ct.index',compact('reportTypes'));       
    //     } catch (\Exception $e) {
    //         $e_method = "ut_ct_report_index";
    //         return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
    //     }
    // }

    public function formControlShow(Request $request)
    {
        try {
            $role_id = MyFuncs::getUserRoleId();
            $report_id = intval(Crypt::decrypt($request->id));
            $have_permission = MyFuncs::isPermission_reports($role_id, $report_id);
            if (! $have_permission){
                return "Not Permission";
            }
            if($report_id == 1){
                $rs_district = SelectBox::get_district_access_list_v1();
                return view('admin.report.master_data.form_1',compact('rs_district'));
            }elseif($report_id == 21){
                $rs_district = SelectBox::get_district_access_list_v1();
                return view('admin.report.master_data.form_2',compact('rs_district'));
            }elseif($report_id == 22){
                $rs_district = SelectBox::get_district_access_list_v1();
                return view('admin.report.master_data.form_2',compact('rs_district'));
            }elseif($report_id == 4){
                $rs_district = SelectBox::get_district_access_list_v1();
                return view('admin.report.master_data.form_4',compact('rs_district'));
            }elseif($report_id == 5){
                $rs_district = SelectBox::get_district_access_list_v1();
                return view('admin.report.master_data.form_2',compact('rs_district'));
            }elseif($report_id == 6){
                $rs_district = SelectBox::get_district_access_list_v1();
                return view('admin.report.master_data.form_2',compact('rs_district'));
            }elseif($report_id == 7){
                $rs_district = SelectBox::get_district_access_list_v1();
                return view('admin.report.master_data.form_2',compact('rs_district'));
            }elseif($report_id == 8){
                $rs_district = SelectBox::get_district_access_list_v1();
                return view('admin.report.master_data.form_2',compact('rs_district'));
            }elseif($report_id == 2001){
                return view('admin.report.no_control_form');
            }        
        } catch (\Exception $e) {
            $e_method = "formControlShow";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
    }

    public function show(Request $request)
    {
        try {
            $show_total_row = 1;
            $result_type = 1;
            $role_id = MyFuncs::getUserRoleId();
            $report_type = intval(Crypt::decrypt($request->report_type));
            if($report_type == 0){
                $response=array();
                $response["status"]=0;
                $response["msg"]='Please Select Report Type';
                return response()->json($response);  
            }

            $have_permission = MyFuncs::isPermission_reports($role_id, $report_type);
            if (! $have_permission){
                $response=array();
                $response["status"]=0;
                $response["msg"]='Not Permission';
                return response()->json($response);
            }
            
            if($report_type == 1){
                if($request->district == 'null' || empty($request->district)){
                    $d_id = 0;
                }else{
                    $d_id = intval(Crypt::decrypt($request->district));
                }
                

                $permission_flag = MyFuncs::check_district_access($d_id);
                if($permission_flag == 0){
                    $d_id = 0;
                }

                if($request->assembly == 'null' || empty($request->assembly)){
                    $ac_id = 0;
                }else{
                    $ac_id = intval(Crypt::decrypt($request->assembly));    
                }
                

                $result_type = 2;
                $show_total_row = 0;
                $tcols = 4;
                $qcols = array(         //Column Caption, Column Width, Field Name, is Numeric, Last Row Values (Total), text-alignment (left, right, center, justify) 
                    array('Part No.',25, 'part_no', 0, '', 'left'),
                    array('First Sr. No.',25, 'first', 0, '', 'left'),
                    array('Last Sr. No.',25, 'last', 0, '', 'left'),
                    array('Total Voters',25, 'total_voters', 0, '', 'left'),
                );

                $rs_result=DB::select(DB::raw("SELECT `ap`.`part_no`, `vtd`.`first`, `vtd`.`last`, `vtd`.`total_voters` from `assembly_parts` `ap` inner join (select `vt`.`assembly_part_id`, count(*) as `total_voters`, min(`vt`.`sr_no`) as `first`, max(`vt`.`sr_no`) as `last` from `voters` `vt` where `vt`.`district_id` = $d_id and `vt`.`assembly_id` = $ac_id group by `vt`.`assembly_part_id`) `vtd` on `vtd`.`assembly_part_id` = `ap`.`id` where `ap`.`assembly_id` = $ac_id order by `ap`.`part_no`;"));
            }elseif($report_type == 21){
                if($request->district == null || empty($request->district)){
                    $d_id = 0;
                }else{
                    $d_id = intval(Crypt::decrypt($request->district));
                }                

                $permission_flag = MyFuncs::check_district_access($d_id);
                if($permission_flag == 0){
                    $d_id = 0;
                }

                if($request->block == null || empty($request->block)){
                    $b_id = 0;
                }else{
                    $b_id = intval(Crypt::decrypt($request->block));    
                }

                $permission_flag = MyFuncs::check_block_access($b_id);
                if($permission_flag == 0){
                    $b_id = 0;
                }

                if($request->village == null || empty($request->village)){
                    $vil_id = 0;
                }else{
                    $vil_id = intval(Crypt::decrypt($request->village));    
                }

                $permission_flag = MyFuncs::check_village_access($vil_id);
                if($permission_flag == 0){
                    $vil_id = 0;
                }                

                
                $condition = "";
                if($role_id == 4){
                    $condition = " where `v`.`id` = $vil_id ";
                }elseif($role_id == 3){
                    $condition = " where `v`.`blocks_id` = $b_id ";
                    if($vil_id > 0){
                        $condition = " where `v`.`id` = $vil_id ";    
                    }
                }elseif($role_id == 2){
                    $condition = " Where `v`.`districts_id` = $d_id ";
                    if($b_id > 0){
                        $condition = " where `v`.`blocks_id` = $b_id ";    
                    }
                    if($vil_id > 0){
                        $condition = " where `v`.`id` = $vil_id ";    
                    }
                }else{
                    if($d_id > 0){
                        $condition = " Where `v`.`districts_id` = $d_id ";    
                    }
                    if($b_id > 0){
                        $condition = " where `v`.`blocks_id` = $b_id ";    
                    }
                    if($vil_id > 0){
                        $condition = " where `v`.`id` = $vil_id ";    
                    }   
                }

                
                $result_type = 2;
                $show_total_row = 0;
                $tcols = 7;
                $qcols = array(         //Column Caption, Column Width, Field Name, is Numeric, Last Row Values (Total), text-alignment (left, right, center, justify) 
                    array('District', 20, 'd_name', 0, '', 'left'),
                    array('Block/MC', 20, 'b_name', 0, '', 'left'),
                    array('Panchayat/MC', 20, 'v_name', 0, '', 'left'),
                    array('Male', 10, 'tmale', 0, '', 'left'),
                    array('Female', 10, 'tfemale', 0, '', 'left'),
                    array('Other', 10, 'third', 0, '', 'left'),
                    array('Total', 10, 'tvote', 0, '', 'left'),
                );
                
                $query = "SELECT `s_v`.`d_name`, `s_v`.`b_name`, `s_v`.`v_name`, (Select Count(`id`) from `voters` where `status` <> 2 and `gender_id` = 1 and `village_id` = `s_v`.`id`) as `tmale`, (Select Count(`id`) from `voters` where `status` <> 2 and `gender_id` = 2 and `village_id` = `s_v`.`id`) as `tfemale`, (Select Count(`id`) from `voters` where `status` <> 2 and `gender_id` = 3 and `village_id` = `s_v`.`id`) as `third`, (Select Count(`id`) from `voters` where `status` <> 2 and `village_id` = `s_v`.`id`) as `tvote` from `villages` `vil` inner join ( select `v`.`id`, `dist`.`name_e` as `d_name`, `b`.`name_e` as `b_name`, `v`.`name_e` as `v_name` from `villages` `v` Inner Join `blocks_mcs` `b` on `b`.`id` = `v`.`blocks_id` inner join `districts` `dist` on `dist`.`id` = `b`.`districts_id` $condition) `s_v` on `s_v`.`id` = `vil`.`id` Order By `s_v`.`d_name`, `s_v`.`b_name`, `s_v`.`v_name`;";
                $rs_result = DB::select(DB::raw("$query"));

                
            }elseif($report_type == 22){
                if($request->district == null || empty($request->district)){
                    $d_id = 0;
                }else{
                    $d_id = intval(Crypt::decrypt($request->district));
                }                

                $permission_flag = MyFuncs::check_district_access($d_id);
                if($permission_flag == 0){
                    $d_id = 0;
                }

                if($request->block == null || empty($request->block)){
                    $b_id = 0;
                }else{
                    $b_id = intval(Crypt::decrypt($request->block));    
                }

                $permission_flag = MyFuncs::check_block_access($b_id);
                if($permission_flag == 0){
                    $b_id = 0;
                }

                if($request->village == null || empty($request->village)){
                    $vil_id = 0;
                }else{
                    $vil_id = intval(Crypt::decrypt($request->village));    
                }

                $permission_flag = MyFuncs::check_village_access($vil_id);
                if($permission_flag == 0){
                    $vil_id = 0;
                }

                $condition = "";
                if($role_id == 4){
                    $condition = " where `v`.`id` = $vil_id ";
                }elseif($role_id == 3){
                    $condition = " where `v`.`blocks_id` = $b_id ";
                    if($vil_id > 0){
                        $condition = " where `v`.`id` = $vil_id ";    
                    }
                }elseif($role_id == 2){
                    $condition = " Where `v`.`districts_id` = $d_id ";
                    if($b_id > 0){
                        $condition = " where `v`.`blocks_id` = $b_id ";    
                    }
                    if($vil_id > 0){
                        $condition = " where `v`.`id` = $vil_id ";    
                    }
                }else{
                    if($d_id > 0){
                        $condition = " Where `v`.`districts_id` = $d_id ";    
                    }
                    if($b_id > 0){
                        $condition = " where `v`.`blocks_id` = $b_id ";    
                    }
                    if($vil_id > 0){
                        $condition = " where `v`.`id` = $vil_id ";    
                    }   
                }

                $result_type = 2;
                $show_total_row = 0;
                $tcols = 8;
                $qcols = array(         //Column Caption, Column Width, Field Name, is Numeric, Last Row Values (Total), text-alignment (left, right, center, justify) 
                    array('District', 15, 'd_name', 0, '', 'left'),
                    array('Block/MC', 15, 'b_name', 0, '', 'left'),
                    array('Panchayat/MC', 15, 'v_name', 0, '', 'left'),
                    array('Ward No.', 15, 'ward_no', 0, '', 'left'),
                    array('Male', 10, 'tmale', 0, '', 'left'),
                    array('Female', 10, 'tfemale', 0, '', 'left'),
                    array('Other', 10, 'third', 0, '', 'left'),
                    array('Total', 10, 'tvote', 0, '', 'left'),
                );
                
                $query = "SELECT `s_v`.`d_name`, `s_v`.`b_name`, `s_v`.`v_name`, `s_v`.`ward_no`, (Select Count(`id`) from `voters` where `status` <> 2 and `gender_id` = 1 and `ward_id` = `s_v`.`id`) as `tmale`, (Select Count(`id`) from `voters` where `status` <> 2 and `gender_id` = 2 and `ward_id` = `s_v`.`id`) as `tfemale`, (Select Count(`id`) from `voters` where `status` <> 2 and `gender_id` = 3 and `ward_id` = `s_v`.`id`) as `third`, (Select Count(`id`) from `voters` where `status` <> 2 and `ward_id` = `s_v`.`id`) as `tvote` from `ward_villages` `w_vil` inner join ( select `wv`.`id`, `dist`.`name_e` as `d_name`, `b`.`name_e` as `b_name`, `v`.`name_e` as `v_name`, `wv`.`ward_no` from `villages` `v` Inner Join `blocks_mcs` `b` on `b`.`id` = `v`.`blocks_id` inner join `districts` `dist` on `dist`.`id` = `b`.`districts_id` inner join `ward_villages` `wv` on `wv`.`village_id` = `v`.`id` $condition) `s_v` on `s_v`.`id` = `w_vil`.`id` Order By `s_v`.`d_name`, `s_v`.`b_name`, `s_v`.`v_name`, `s_v`.`ward_no`;";

                $rs_result = DB::select(DB::raw("$query"));

                
            }elseif($report_type == 4){
                if($request->district == null || empty($request->district)){
                    $d_id = 0;
                }else{
                    $d_id = intval(Crypt::decrypt($request->district));
                }                

                $permission_flag = MyFuncs::check_district_access($d_id);
                if($permission_flag == 0){
                    $d_id = 0;
                }

                if($request->block == null || empty($request->block)){
                    $b_id = 0;
                }else{
                    $b_id = intval(Crypt::decrypt($request->block));    
                }

                $permission_flag = MyFuncs::check_block_access($b_id);
                if($permission_flag == 0){
                    $b_id = 0;
                }

                if($request->village == null || empty($request->village)){
                    $vil_id = 0;
                }else{
                    $vil_id = intval(Crypt::decrypt($request->village));    
                }

                $permission_flag = MyFuncs::check_village_access($vil_id);
                if($permission_flag == 0){
                    $vil_id = 0;
                }

                if($request->ward == null || empty($request->ward)){
                    $ward_id = 0;
                }else{
                    $ward_id = intval(Crypt::decrypt($request->ward));    
                }               

                $result_type = 2;
                $show_total_row = 0;
                $tcols = 9;
                $qcols = array(         //Column Caption, Column Width, Field Name, is Numeric, Last Row Values (Total), text-alignment (left, right, center, justify) 
                    array('Block Name',10, 'block_name', 0, '', 'left'),
                    array('Village Name',10, 'name_e', 0, '', 'left'),
                    array('Booth No.',10, 'booth_no', 0, '', 'left'),
                    array('Booth (E)',10, 'booth_e', 0, '', 'left'),
                    array('Booth (H)',10, 'booth_l', 0, '', 'left'),
                    array('Male',10, 'tmale', 0, '', 'left'),
                    array('Female',10, 'tfemale', 0, '', 'left'),
                    array('Other',10, 'third', 0, '', 'left'),
                    array('Total',10, 'tvote', 0, '', 'left'),
                );
                $condition = "";
                if($d_id > 0){
                    $condition = " Where `v`.`districts_id` = $d_id ";
                }elseif($b_id > 0){
                    $condition = " where `v`.`blocks_id` = $b_id ";
                }elseif($vil_id > 0){
                    $condition = " where `v`.`id` = $vil_id ";
                }elseif($ward_id > 0){
                    $condition = " where `wv`.`id` = $ward_id ";
                }

                $query = "SELECT `b`.`name_e` as `block_name`, `v`.`name_e`, concat(`pb`.`booth_no`, ifnull(`pb`.`booth_no_c`,'')) as `booth_no`, `pb`.`name_e` as `booth_e`, `pb`.`name_l` as `booth_l`, (Select Count(`id`) from `voters` where `status` <> 2 and `gender_id` = 1 and `booth_id` = `pb`.`id`) as `tmale`, (Select Count(`id`) from `voters` where `status` <> 2 and `gender_id` = 2 and `booth_id` = `pb`.`id`) as `tfemale`, (Select Count(`id`) from `voters` where `status` <> 2 and `gender_id` = 3 and `booth_id` = `pb`.`id`) as `third`, (Select Count(`id`) from `voters` where `status` <> 2 and `booth_id` = `pb`.`id`) as `tvote` from `polling_booths` `pb` Inner Join `villages` `v` on `v`.`id` = `pb`.`village_id` Inner Join `blocks_mcs` `b` on `b`.`id` = `v`.`blocks_id` $condition Order By `v`.`districts_id`, `b`.`name_e`, `v`.`name_e`, `pb`.`booth_no`;";

                $rs_result = DB::select(DB::raw("$query"));

                
            }elseif ($report_type == 5){
                if($request->district == null || empty($request->district)){
                    $d_id = 0;
                }else{
                    $d_id = intval(Crypt::decrypt($request->district));
                }                

                $permission_flag = MyFuncs::check_district_access($d_id);
                if($permission_flag == 0){
                    $d_id = 0;
                }

                if($request->block == null || empty($request->block)){
                    $b_id = 0;
                }else{
                    $b_id = intval(Crypt::decrypt($request->block));    
                }

                $permission_flag = MyFuncs::check_block_access($b_id);
                if($permission_flag == 0){
                    $b_id = 0;
                }

                if($request->village == null || empty($request->village)){
                    $vil_id = 0;
                }else{
                    $vil_id = intval(Crypt::decrypt($request->village));    
                }

                $permission_flag = MyFuncs::check_village_access($vil_id);
                if($permission_flag == 0){
                    $vil_id = 0;
                }
                $result_type = 2;
                $show_total_row = 0;
                $tcols = 4;
                $qcols = array(         //Column Caption, Column Width, Field Name, is Numeric, Last Row Values (Total), text-alignment (left, right, center, justify) 
                    array('Part No.',25, 'block_name', 0, '', 'left'),
                    array('From Sr. No.',25, 'name_e', 0, '', 'left'),
                    array('To Sr. No.',25, 'booth_no', 0, '', 'left'),
                    array('Ward No.',25, 'booth_e', 0, '', 'left'),
                );

                $rs_result=DB::select(DB::raw("call `up_prepare_asmb_part_srn_list_wardwise_report`($vil_id);"));
                $query = 'SELECT `partno`, `fromsrno`, `tosrno`, `wardno` from `voters_srno_detail_village` where `village_id` = '.$vil_id. ' order by `partno`, `fromsrno`';
                $rs_result = DB::select(DB::raw("$query"));
            }elseif ($report_type == 6){
                if($request->district == null || empty($request->district)){
                    $d_id = 0;
                }else{
                    $d_id = intval(Crypt::decrypt($request->district));
                }                

                $permission_flag = MyFuncs::check_district_access($d_id);
                if($permission_flag == 0){
                    $d_id = 0;
                }

                if($request->block == null || empty($request->block)){
                    $b_id = 0;
                }else{
                    $b_id = intval(Crypt::decrypt($request->block));    
                }

                $permission_flag = MyFuncs::check_block_access($b_id);
                if($permission_flag == 0){
                    $b_id = 0;
                }

                if($request->village == null || empty($request->village)){
                    $vil_id = 0;
                }else{
                    $vil_id = intval(Crypt::decrypt($request->village));    
                }

                $permission_flag = MyFuncs::check_village_access($vil_id);
                if($permission_flag == 0){
                    $vil_id = 0;
                }
                $result_type = 2;
                $show_total_row = 0;
                $tcols = 6;
                $qcols = array(         //Column Caption, Column Width, Field Name, is Numeric, Last Row Values (Total), text-alignment (left, right, center, justify) 
                    array('Ac No.',25, 'block_name', 0, '', 'left'),
                    array('Part No.',10, 'name_e', 0, '', 'left'),
                    array('From Sr. No.',10, 'booth_no', 0, '', 'left'),
                    array('To Sr. No.',10, 'booth_e', 0, '', 'left'),
                    array('Ward No.',10, 'booth_e', 0, '', 'left'),
                    array('Booth No.',10, 'booth_e', 0, '', 'left'),
                );
                $rs_result = DB::select(DB::raw("call `up_prepare_asmb_part_srn_list_wardwise__for_mc_report`($vil_id);"));
                $query = 'SELECT `acno`, `partno`, `fromsrno`, `tosrno`, `wardno`, `booth_no` from `voters_srno_detail_mc` where `village_id` = '.$vil_id. ' order by `acno`, `partno`, `fromsrno`';
                $rs_result = DB::select(DB::raw("$query"));
            }elseif ($report_type == 7){
                if($request->district == null || empty($request->district)){
                    $d_id = 0;
                }else{
                    $d_id = intval(Crypt::decrypt($request->district));
                }                

                $permission_flag = MyFuncs::check_district_access($d_id);
                if($permission_flag == 0){
                    $d_id = 0;
                }

                if($request->block == null || empty($request->block)){
                    $b_id = 0;
                }else{
                    $b_id = intval(Crypt::decrypt($request->block));    
                }

                $permission_flag = MyFuncs::check_block_access($b_id);
                if($permission_flag == 0){
                    $b_id = 0;
                }

                if($request->village == null || empty($request->village)){
                    $vil_id = 0;
                }else{
                    $vil_id = intval(Crypt::decrypt($request->village));    
                }

                $permission_flag = MyFuncs::check_village_access($vil_id);
                if($permission_flag == 0){
                    $vil_id = 0;
                }

                $result_type = 2;
                $show_total_row = 0;
                
                $tcols = 5;
                $qcols = array(         //Column Caption, Column Width, Field Name, is Numeric, Last Row Values (Total), text-alignment (left, right, center, justify) 
                    array('Block Name',15, 'block_name', 0, '', 'left'),
                    array('Village Name (E)',15, 'name_e', 0, '', 'left'),
                    array('Village Name (H)',15, 'name_l', 0, '', 'left'),
                    array('Total Wards',15, 'twards', 0, '', 'left'),
                    array('Zila Parishad Ward No.',15, 'zp_wardno', 0, '', 'left'),
                );
                $condition = "";
                if($vil_id > 0){
                    $condition = "where `v`.`id` = $vil_id ";
                }elseif($b_id > 0){
                    $condition = "where `v`.`blocks_id` = $b_id ";
                }elseif($d_id > 0){
                    $condition = "where `v`.`districts_id` = $d_id ";
                }
                $query = "SELECT `b`.`name_e` as `block_name`, `v`.`name_e`, `v`.`name_l`, (Select Count(*) From `ward_villages` `wv` Where `wv`.`village_id` = `v`.`id`) as `twards`, `wz`.`ward_no` as `zp_wardno` from `villages` `v` Inner Join `blocks_mcs` `b` on `b`.`id` = `v`.`blocks_id` Left Join `ward_zp` `wz` on `wz`.`id` = `v`.`zp_ward_id` $condition Order By `v`.`districts_id`, `b`.`name_e`, `v`.`name_e`;";
                $rs_result = DB::select(DB::raw("$query"));
            }elseif ($report_type == 8){
                if($request->district == null || empty($request->district)){
                    $d_id = 0;
                }else{
                    $d_id = intval(Crypt::decrypt($request->district));
                }                

                $permission_flag = MyFuncs::check_district_access($d_id);
                if($permission_flag == 0){
                    $d_id = 0;
                }

                if($request->block == null || empty($request->block)){
                    $b_id = 0;
                }else{
                    $b_id = intval(Crypt::decrypt($request->block));    
                }

                $permission_flag = MyFuncs::check_block_access($b_id);
                if($permission_flag == 0){
                    $b_id = 0;
                }

                if($request->village == null || empty($request->village)){
                    $vil_id = 0;
                }else{
                    $vil_id = intval(Crypt::decrypt($request->village));    
                }

                $permission_flag = MyFuncs::check_village_access($vil_id);
                if($permission_flag == 0){
                    $vil_id = 0;
                }

                $result_type = 2;
                $show_total_row = 0;
                
                $tcols = 5;
                $qcols = array(         //Column Caption, Column Width, Field Name, is Numeric, Last Row Values (Total), text-alignment (left, right, center, justify) 
                    array('Block Name',15, 'bl_name', 0, '', 'left'),
                    array('Panchayat Name',15, 'vil_name', 0, '', 'left'),
                    array('Booth No.',15, 'boothno', 0, '', 'left'),
                    array('Booth Name (E)',15, 'name_e', 0, '', 'left'),
                    array('Booth Name (H).',15, 'name_l', 0, '', 'left'),
                );

                
                $condition = "";
                if($b_id > 0){
                    $condition = "where `pb`.`blocks_id` = $b_id ";
                }elseif($d_id > 0){
                    $condition = "where `pb`.`districts_id` = $d_id ";
                }

                $query = "SELECT `bl`.`name_e` as `bl_name`, `vil`.`name_e` as `vil_name`, concat(`pb`.`booth_no`,`pb`.`booth_no_c`) as `boothno`, `pb`.`name_e`, `pb`.`name_l` from `polling_booths` `pb` inner join `blocks_mcs` `bl` on `bl`.`id` = `pb`.`blocks_id` left join `villages` `vil` on `vil`.`id` = `pb`.`village_id`  $condition Order By `bl`.`name_e`, `pb`.`booth_no`,`pb`.`booth_no_c`;";
                $rs_result = DB::select(DB::raw("$query"));
            }elseif ($report_type == 2000){
                $tcols = 2;
                $qcols = array(
                    array('Class Section',40),
                    array('Class Teacher',60),
                ); 
                $rs_result=DB::select(DB::raw("SELECT concat(`ct`.`name`, ' - ', `st`.`name`) as `class_section_name`, `uf_get_class_teacher_details`(`sec`.`id`) as `class_teacher` from `sections` `sec` inner join `class_types` `ct` on `ct`.`id` = `sec`.`class_id` inner join `section_types` `st` on `st`.`id` = `sec`.`section_id` where `sec`.`status` = 1 and `uf_get_daily_homework_status_section`(`sec`.`id`, curdate()) = 0 order by `ct`.`shorting_id`, `st`.`sorting_order_id`;"));
            }elseif($report_type == 2001){
                $result_type = 2;
                $show_total_row = 0;
                $tcols = 2;
                $qcols = array(         //Column Caption, Column Width, Field Name, is Numeric, Last Row Values (Total), text-alignment (left, right, center, justify) 
                    array('Class-Section',60, 'class_name', 0, '', 'left'),
                    array('Total Students',40, 'count', 0, '', 'center'),
                );

                $rs_result=DB::select(DB::raw("SELECT concat(`ct`.`name`, ' - ', `sect`.`name`) as `class_name`, count(*) as `count` from `students` `si` inner join `sections` `sec` on `sec`.`id` = `si`.`class_section_id` inner join `class_types` `ct` on `ct`.`id` = `sec`.`class_id` inner join `section_types` `sect` on `sect`.`id` = `sec`.`section_id` where `si`.`student_status_id` = 1 group by `sect`.`name`, `ct`.`name`, `ct`.`shorting_id`, `sect`.`sorting_order_id` order by `ct`.`shorting_id`, `sect`.`sorting_order_id`;"));
            }

            $response = array();
            $response['status'] = 1; 
            if($result_type == 1){
                $response['data'] =view('admin.report.result',compact('rs_result', 'tcols', 'qcols'))->render();
            }elseif($result_type == 2){
                $response['data'] =view('admin.report.result_02',compact('rs_result', 'tcols', 'qcols', 'show_total_row'))->render();
            }
            return response()->json($response);           
        } catch (Exception $e) {
            $e_method = "show";
            return MyFuncs::Exception_error_handler($this->e_controller, $e_method, $e->getMessage());
        }
        
    }

}

