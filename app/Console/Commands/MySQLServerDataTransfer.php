<?php

namespace App\Console\Commands;

use App\Admin;
use App\Helper\MyFuncs;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class MySQLServerDataTransfer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mysqlServerDataTransfer:transfer {from_district} {from_block} {from_panchayat} {to_district} {to_block} {to_panchyat}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'mysqlServerDataTransfer Transfer ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    { 
        ini_set('max_execution_time', '7200');
        ini_set('memory_limit','999M');
        ini_set("pcre.backtrack_limit", "100000000");
        
        $from_district = $this->argument('from_district');
        $from_block = $this->argument('from_block'); 
        $from_panchayat = $this->argument('from_panchayat'); 
        $to_district = $this->argument('to_district'); 
        $to_block = $this->argument('to_block'); 
        $to_panchyat = $this->argument('to_panchyat'); 

        $rs_result = DB::select(DB::raw("select * from `voters` where `village_id` = $to_panchyat and `data_list_id` = 1 limit 1;"));
        if(count($rs_result) > 0){
            $remarks = 'Not Able to Import as data Already Exist for this Panchayat/MC';
            $rs_update = DB::select(DB::raw("insert into `import_draft_status` (`remarks`) values ('$remarks');")); 
            return null;
        }

        $rs_update = DB::select(DB::raw("truncate table `import_draft_status`;"));
        $rs_update = DB::select(DB::raw("truncate table `draft_voter_data`;"));

        $data_import_detail = DB::select(DB::raw("select * from `import_type` where `status` = 1 limit 1;"));
        $data_import_id = $data_import_detail[0]->id;
      
        

        $rs_ac_partno = DB::connection('mysql_remote')->select("select distinct `assembly_id`, `assembly_part_id` from `voters` where `village_id` = $from_panchayat order by `assembly_id`, `assembly_part_id`;");

        if(count($rs_ac_partno) == 0){
            $remarks = 'AC No and Part No. Not Exists';
            $rs_update = DB::select(DB::raw("insert into `import_draft_status` (`remarks`) values ('$remarks');")); 
            return null;
        }

        foreach ($rs_ac_partno as $key => $val_ac_partno) {
            
            $rs_data = DB::connection('mysql_remote')->select("select `code` from `assemblys` where `id` = $val_ac_partno->assembly_id;");
            $ac_code = $rs_data[0]->code;

            $rs_data = DB::connection('mysql_remote')->select("select `part_no` from `assembly_parts` where `id` = $val_ac_partno->assembly_part_id;");
            $ac_part_no = $rs_data[0]->part_no;

            $rs_data = DB::select(DB::raw("select * from `assemblys` where `code` = '$ac_code' and `district_id` = $to_district limit 1;"));
            if(count($rs_data) == 0){
                $remarks = 'AC ID Not Exists for AC :: '.$ac_code;
                $rs_update = DB::select(DB::raw("insert into `import_draft_status` (`remarks`) values ('$remarks');")); 
                continue;    
            }
            $l_ac_id = $rs_data[0]->id;
            
            $rs_data = DB::select(DB::raw("select * from `assembly_parts` where `part_no` = '$ac_part_no' and `assembly_id` = $l_ac_id limit 1;"));
            if(count($rs_data) == 0){
                $remarks = 'Part ID Not Exists for Part No. :: '.$ac_code.' - '.$ac_part_no;
                $rs_update = DB::select(DB::raw("insert into `import_draft_status` (`remarks`) values ('$remarks');")); 
                continue;    
            }
            $l_part_id = $rs_data[0]->id;

            $rs_gp_ward = DB::connection('mysql_remote')->select("select distinct `ward_id`, `booth_id` from `voters` where `village_id` = $from_panchayat and `assembly_id` = $val_ac_partno->assembly_id and `assembly_part_id` = $val_ac_partno->assembly_part_id order by `ward_id`;");          

            if(count($rs_gp_ward) == 0){
                $remarks = 'Ward Nos Not Exists for Panchayat Selected ';
                $rs_update = DB::select(DB::raw("insert into `import_draft_status` (`remarks`) values ('$remarks');")); 
                continue;
            }

            foreach ($rs_gp_ward as $key => $val_gp_ward) {
                $rs_data = DB::connection('mysql_remote')->select("select * from `ward_villages` where `id` = $val_gp_ward->ward_id;");
                $ward_no = $rs_data[0]->ward_no;


                $rs_data = DB::select(DB::raw("select * from `ward_villages` where `districts_id` = $to_district and `blocks_id` = $to_block and `village_id` = $to_panchyat and `ward_no` = $ward_no limit 1;"));
                if(count($rs_data) == 0){
                    $remarks = 'Ward Id Not Exists for Ward No. :: '.$ward_no;
                    $rs_update = DB::select(DB::raw("insert into `import_draft_status` (`remarks`) values ('$remarks');")); 
                    continue;    
                }   
                $l_ward_id = $rs_data[0]->id;


                if($val_gp_ward->booth_id > 0){
                    $rs_data = DB::connection('mysql_remote')->select("select `booth_no`, ifnull(booth_no_c,'') as `a_booth_no` from `polling_booths` where `id` = $val_gp_ward->booth_id;");
                    $booth_no = $rs_data[0]->booth_no;
                    $a_booth_no = $rs_data[0]->a_booth_no;

                    $rs_data = DB::select(DB::raw("select * from `polling_booths` where `districts_id` = $to_district and `blocks_id` = $to_block and `village_id` = $to_panchyat and `booth_no` = $booth_no and `booth_no_c` = '$a_booth_no' limit 1;"));
                    if(count($rs_data) == 0){
                        $remarks = 'Booth Id Not Exists for Booth No. :: '.$booth_no.$a_booth_no;
                        $rs_update = DB::select(DB::raw("insert into `import_draft_status` (`remarks`) values ('$remarks');")); 
                        continue;    
                    }   
                    $l_booth_id = $rs_data[0]->id;
                }else{
                    $l_booth_id = 0;
                }


                $rs_gp_voters = DB::connection('mysql_remote')->select("select * from `voters` where `village_id` = '$from_panchayat' and `assembly_id` = $val_ac_partno->assembly_id and `assembly_part_id` = $val_ac_partno->assembly_part_id and `ward_id` = '$val_gp_ward->ward_id' and `booth_id` = '$val_gp_ward->booth_id' and `status` in (0, 1, 3) order by sr_no");  

                if(count($rs_gp_voters) == 0){
                    $remarks = 'No Data Found for Ward and Booth :: '.$ward_no.' - '.$booth_no.$a_booth_no;
                    $rs_update = DB::select(DB::raw("insert into `import_draft_status` (`remarks`) values ('$remarks');")); 
                    continue;
                }

                foreach ($rs_gp_voters as $key => $value) {
                    $o_suppliment = 0;
                    $o_status = $value->status;

                    $name_l=$value->name_l;
                    
                    $name_e=$value->name_e;
                    
                    $f_name_e=$value->father_name_e;
                    
                    $f_name_l=$value->father_name_l;
                    
                    $relation=$value->relation;  
                    
                    $gender_id=$value->gender_id;
                    
                    $house_e = $value->house_no_e;
                    
                    $house_l = $value->house_no_l;
                    
                    $newId = DB::select(DB::raw("call up_save_voter_detail_draft_data($to_district, $l_ac_id, $l_part_id, $value->sr_no, '$value->voter_card_no', '$house_e', '$house_l','','$name_e','$name_l','$f_name_e','$f_name_l', $relation, $gender_id, $value->age, '$value->mobile_no', 'v', $o_suppliment, $o_status, $to_panchyat, $l_ward_id, $value->print_sr_no, $l_booth_id, $data_import_id);"));
                }

            }

        }


        $rs_remarks = DB::select(DB::raw("select * from `import_draft_status`;"));
        if(count($rs_remarks) == 0){
            
            $rs_delete = DB::select(DB::raw("call `up_process_import_draft_deleted_midified_voters`($to_panchyat);"));
            
            $rs_update = DB::select(DB::raw("call `up_process_import_draft_set_srno_voters`($to_panchyat);"));

        }

        


       
        $remarks = 'Porting Completed';
        $rs_update = DB::select(DB::raw("insert into `import_draft_status` (`remarks`) values ('$remarks');")); 
        $rs_remarks = DB::select(DB::raw("select * from `import_draft_status`;"));
        foreach ($rs_remarks as $key => $val_remark) {
            echo($val_remark->remarks."\n");
        }

      
    }

         
       
}
