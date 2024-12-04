<?php

namespace App\Console\Commands; 
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\mysqli;

class oUploadProdairy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:prodairy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'upload prodairy';

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
        ini_set('max_execution_time', '72000');
        ini_set('memory_limit','999M');
        ini_set("pcre.backtrack_limit", "5000000");


        

        $servername = "162.214.94.136";
        $username = "eageskoo_1";
        $password = "Ashok@2342";
        $dbname = "eageskoo_apodairy";
        $conn = mysqli_connect($servername, $username, $password, $dbname);

        mysqli_set_charset($conn,"utf8");

        
        $v_districts = DB::select(DB::raw("select * from `districts` order by `id`;"));
        foreach ($v_districts as $key => $district_value) {

            $dcode = $district_value->code;
            $did = $district_value->id;

            $sql = "select * from `districts` where `code` = '$dcode';";
            if ($result = $conn -> query($sql)) {
                while ($row = $result -> fetch_row()) {
                    $s_did = $row[0];
                    
                    $v_blocks = DB::select(DB::raw("select * from `blocks_mcs` where `districts_id` = $did order by `id`;"));
                    $sql_block = "insert into `blocks_mcs` (`id`, `states_id`, `districts_id`, `code`, `name_e`, `name_l`, `block_mc_type_id`, `stamp_l1`, `stamp_l2`) values ";
                    $sql_block_val = '';
                    foreach ($v_blocks as $key => $block_value){
                        if($sql_block_val != ''){
                            $sql_block_val = $sql_block_val.', ';
                        }
                        $sql_block_val = $sql_block_val."($block_value->id, 1, $s_did, '$block_value->code', '$block_value->name_e', '$block_value->name_l', $block_value->block_mc_type_id, '$block_value->stamp_l1', '$block_value->stamp_l2')";
                        

                        $v_villages = DB::select(DB::raw("select * from `villages` where `districts_id` = $did and `blocks_id` = $block_value->id order by `id`;"));
                        $sql = "insert into `villages` (`id`, `states_id`, `districts_id`, `blocks_id`, `code`, `name_e`, `name_l`, `ps_ward_id`, `zp_ward_id`, `is_locked`) values ";
                        $sql_values = '';    
                        foreach ($v_villages as $key => $village_value){
                            if($sql_values != ''){
                                $sql_values = $sql_values.', ';
                            }
                            $sql_values = $sql_values."($village_value->id, 1, $s_did, $village_value->blocks_id, '$village_value->code', '$village_value->name_e', '$village_value->name_l', $village_value->ps_ward_id, '$village_value->zp_ward_id', '$village_value->is_locked')";


                            $v_wards = DB::select(DB::raw("select * from `ward_villages` where `districts_id` = $did and `blocks_id` = $block_value->id and `village_id` = $village_value->id order by `id`;"));
                            $sql_wards = "insert into `ward_villages` (`id`, `states_id`, `districts_id`, `blocks_id`, `village_id`, `ward_no`, `name_e`, `name_l`, `is_locked`, `ps_ward_id`) values ";
                            $sql_wards_val = '';
                            foreach ($v_wards as $key => $ward_value){
                                if($sql_wards_val != ''){
                                    $sql_wards_val = $sql_wards_val.', ';
                                }
                                $sql_wards_val = $sql_wards_val."($ward_value->id, 1, $s_did, $ward_value->blocks_id, $ward_value->village_id, '$ward_value->ward_no', '$ward_value->name_e', '$ward_value->name_l', $ward_value->is_locked, '$ward_value->ps_ward_id')";

                            }
                            if($sql_wards_val != ''){
                                $sql_wards_val = $sql_wards_val.'; ';
                                $sql_wards = $sql_wards.$sql_wards_val;
                                $s_update = $conn -> query($sql_wards);
                            }

                        }
                        if($sql_values != ''){
                            $sql_values = $sql_values.';';
                            $sql = $sql.$sql_values;
                            $s_update = $conn -> query($sql);
                        }

                        $sql_booth_val = '';
                        $sql_activity_val = '';
                        $v_booths = DB::select(DB::raw("select `id`, `states_id`, `districts_id`, `blocks_id`, `village_id`, `booth_no`, `name_e`, `name_l`, `booth_no_c`, 
                            (select count(*) from `voters` where `booth_id` = `pb`.`id` and `status` <> 2) as `tvoter`,
                            (select count(*) from `voters` where `booth_id` = `pb`.`id` and `status` <> 2 and `gender_id` = 1) as `tmale`,
                            (select count(*) from `voters` where `booth_id` = `pb`.`id` and `status` <> 2 and `gender_id` = 2) as `tfemale`,
                            (select count(*) from `voters` where `booth_id` = `pb`.`id` and `status` <> 2 and `gender_id` = 3) as `tthird` 
                            from `polling_booths` `pb`
                            where `pb`.`districts_id` = $did and `pb`.`blocks_id` = $block_value->id;"));
                        foreach ($v_booths as $key => $booths_value){
                            if($sql_booth_val != ''){
                                $sql_booth_val = $sql_booth_val.', ';
                                $sql_activity_val = $sql_activity_val.', ';
                            }
                            $sql_booth_val = $sql_booth_val."($booths_value->id, $booths_value->states_id, $s_did, $booths_value->blocks_id, $booths_value->village_id, '$booths_value->booth_no', '$booths_value->name_e', '$booths_value->name_l', '$booths_value->booth_no_c', $booths_value->tvoter, $booths_value->tmale, $booths_value->tfemale, $booths_value->tthird) ";

                            $sql_activity_val = $sql_activity_val."($booths_value->states_id, $s_did, $booths_value->blocks_id, $booths_value->village_id, '$booths_value->id', '$booths_value->booth_no', '$booths_value->name_e', '$booths_value->name_l', '$booths_value->booth_no_c') ";

                            $sql_voters_val = "";
                            $v_voters = DB::select(DB::raw("select * from `voters` where `booth_id` = '$booths_value->id' and `status` <> 2;"));
                            foreach ($v_voters as $key => $voters_value){
                                if($sql_voters_val != ''){
                                    $sql_voters_val = $sql_voters_val.', ';
                                }
                                $sql_voters_val = $sql_voters_val."($voters_value->id, $s_did, $voters_value->assembly_id, $voters_value->assembly_part_id, '$voters_value->voter_card_no', $voters_value->sr_no, '$voters_value->house_no_e', '$voters_value->house_no_l', $voters_value->house_no, '$voters_value->aadhar_no', '$voters_value->name_e', '$voters_value->name_l', '$voters_value->father_name_e', '$voters_value->father_name_l', '$voters_value->relation', $voters_value->gender_id, $voters_value->age, '$voters_value->mobile_no', $booths_value->blocks_id, $voters_value->village_id, $voters_value->ward_id, $voters_value->print_sr_no, '$voters_value->source', $voters_value->suppliment_no, $voters_value->status, $voters_value->booth_id) ";

                            }
                            if($sql_voters_val != ''){
                                $sql_voters_val = "Insert into `voters` (`id`, `district_id`, `assembly_id`, `assembly_part_id`, `voter_card_no`, `sr_no`, `house_no_e`, `house_no_l`, `house_no`, `aadhar_no`, `name_e`, `name_l`, `father_name_e`, `father_name_l`, `relation`, `gender_id`, `age`, `mobile_no`, `block_id`, `village_id`, `ward_id`, `print_sr_no`, `source`, `suppliment_no`, `status`, `booth_id`) values ".$sql_voters_val.';';
                                $s_update = $conn -> query($sql_voters_val);
                            }



                        }
                        if($sql_booth_val != ''){
                            $sql_booth_insert = "Insert into `polling_booths`(`id`, `states_id`, `districts_id`, `blocks_id`, `village_id`, `booth_no`, `name_e`, `name_l`, `booth_no_c`, `total_voters`, `male_voters`, `female_voters`, `third_voters`) values ".$sql_booth_val.';';
                            $s_update = $conn -> query($sql_booth_insert);

                            $sql_booth_insert = "Insert into `polling_activity_status`(`states_id`, `districts_id`, `blocks_id`, `village_id`, `booth_id`, `booth_no`, `name_e`, `name_l`, `booth_no_c`) values ".$sql_activity_val.';';
                            $s_update = $conn -> query($sql_booth_insert);

                            $sql_booth_insert = "Insert into `activity_status_1`(`states_id`, `districts_id`, `blocks_id`, `village_id`, `booth_id`, `booth_no`, `name_e`, `name_l`, `booth_no_c`) values ".$sql_activity_val.';';
                            $s_update = $conn -> query($sql_booth_insert);

                            $sql_booth_insert = "Insert into `activity_status_2`(`states_id`, `districts_id`, `blocks_id`, `village_id`, `booth_id`, `booth_no`, `name_e`, `name_l`, `booth_no_c`) values ".$sql_activity_val.';';
                            $s_update = $conn -> query($sql_booth_insert);

                            $sql_booth_insert = "Insert into `activity_status_3`(`states_id`, `districts_id`, `blocks_id`, `village_id`, `booth_id`, `booth_no`, `name_e`, `name_l`, `booth_no_c`) values ".$sql_activity_val.';';
                            $s_update = $conn -> query($sql_booth_insert);

                            $sql_booth_insert = "Insert into `activity_status_4`(`states_id`, `districts_id`, `blocks_id`, `village_id`, `booth_id`, `booth_no`, `name_e`, `name_l`, `booth_no_c`) values ".$sql_activity_val.';';
                            $s_update = $conn -> query($sql_booth_insert);

                            $sql_booth_insert = "Insert into `activity_status_5`(`states_id`, `districts_id`, `blocks_id`, `village_id`, `booth_id`, `booth_no`, `name_e`, `name_l`, `booth_no_c`) values ".$sql_activity_val.';';
                            $s_update = $conn -> query($sql_booth_insert);

                            $sql_booth_insert = "Insert into `activity_status_6`(`states_id`, `districts_id`, `blocks_id`, `village_id`, `booth_id`, `booth_no`, `name_e`, `name_l`, `booth_no_c`) values ".$sql_activity_val.';';
                            $s_update = $conn -> query($sql_booth_insert);

                            $sql_booth_insert = "Insert into `activity_status_7`(`states_id`, `districts_id`, `blocks_id`, `village_id`, `booth_id`, `booth_no`, `name_e`, `name_l`, `booth_no_c`) values ".$sql_activity_val.';';
                            $s_update = $conn -> query($sql_booth_insert);

                            $sql_booth_insert = "Insert into `activity_status_8`(`states_id`, `districts_id`, `blocks_id`, `village_id`, `booth_id`, `booth_no`, `name_e`, `name_l`, `booth_no_c`) values ".$sql_activity_val.';';
                            $s_update = $conn -> query($sql_booth_insert);

                            $sql_booth_insert = "Insert into `activity_status_9`(`states_id`, `districts_id`, `blocks_id`, `village_id`, `booth_id`, `booth_no`, `name_e`, `name_l`, `booth_no_c`) values ".$sql_activity_val.';';
                            $s_update = $conn -> query($sql_booth_insert);

                            $sql_booth_insert = "Insert into `activity_status_10`(`states_id`, `districts_id`, `blocks_id`, `village_id`, `booth_id`, `booth_no`, `name_e`, `name_l`, `booth_no_c`) values ".$sql_activity_val.';';
                            $s_update = $conn -> query($sql_booth_insert);

                            $sql_booth_insert = "Insert into `activity_status_11`(`states_id`, `districts_id`, `blocks_id`, `village_id`, `booth_id`, `booth_no`, `name_e`, `name_l`, `booth_no_c`) values ".$sql_activity_val.';';
                            $s_update = $conn -> query($sql_booth_insert);
                        }


                        $sql_appuser_val = '';
                        $v_appusers = DB::select(DB::raw("select * from `app_users` where `districts_id` = $did and blocks_id = $block_value->id"));
                        // dd("select * from `app_users` where `districts_id` = $did and blocks_id = $block_value->id");
                        foreach ($v_appusers as $key => $appuser_value){
                            if($sql_appuser_val != ''){
                                $sql_appuser_val = $sql_appuser_val.', ';
                            }
                            $sql_appuser_val = $sql_appuser_val."('$appuser_value->officer_name', '$appuser_value->designation', '$appuser_value->office_address', '$appuser_value->mobileno', $appuser_value->duty_as, 1, $s_did, $appuser_value->blocks_id, $appuser_value->village_id, $appuser_value->booth_id, $appuser_value->photo_download, $appuser_value->voter_data_download, 0)";    
                        }
                        if($sql_appuser_val != ''){
                            $sql_appuser_val = "Insert into `app_users` (`officer_name`, `designation`, `office_address`, `mobileno`, `duty_as`, `states_id`, `districts_id`, `blocks_id`, `village_id`, `booth_id`, `photo_download`, `voter_data_download`, `app_installed`) values ".$sql_appuser_val.';';
                            // dd($sql_appuser_val);
                            $s_update = $conn -> query($sql_appuser_val);
                        }


                    }
                    if($sql_block_val != ''){
                        $sql_block_val = $sql_block_val.';';
                        $sql_block = $sql_block.$sql_block_val;
                        $s_update = $conn -> query($sql_block);
                    }


                    



                }
                $result -> free_result();
            }
        }
        $conn -> close();
        // mysqli_close($conn); 
    }    
   
}
