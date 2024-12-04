<?php

namespace App\Console\Commands;

use App\Admin;
use App\Helper\MyFuncs;
use App\Model\Assembly;
use App\Model\AssemblyPart;
use App\Model\DefaultValue;
use App\Model\History;
use App\Model\Voter;
use App\Model\WardVillage;
use App\Model\VoterListMaster;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class MysqlDataTransfer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mysqldata:transfer {district_id} {block_id} {village_id} {ward_id}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'mysqldata Transfer ';

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
    // public function handle()
    // {   
    //   $serverName=".";
    //   $database="panchayat";
    //   $username=getenv('sa');
    //   $passward=getenv('Nicjjr@123'); 
    //   // $serverName =$serverName;
    //   $options = array(  "UID" =>$username,  "PWD" =>$passward,  "Database" =>$database, "CharacterSet" => "UTF-8");
    //   $conn = sqlsrv_connect($serverName, $options); 
    //   if( $conn === false )
    //   {
    //   echo "Could not connect.\n";
    //   die( print_r( sqlsrv_errors(), true));
    //   }
      
    //   echo "Porting Stared.".date("d-m-y H:i:s")."\n";

    //   $did = $this->argument('district_id');
    //   $bid = $this->argument('block_id');
    //   $vid = $this->argument('village_id');
    //   $wid = $this->argument('ward_id');
    //   // echo $did."\n";
    //   // echo $this->argument('district_id')."\n";
    //   // die("ok");
    //   $dcondition = "";
    //   $bcondition = "";
    //   $vcondition = "";
    //   $wcondition = "";
    //   $wcodecondition = "";
    //   if($did > 0){
    //     $dcondition = " Where `d`.`id` = $did ";  
    //   }
    //   if($bid > 0){
    //     $bcondition = " And `bl`.`id` = $bid ";  
    //   }
    //   if($vid > 0){
    //     $vcondition = " And `vl`.`id` = $vid ";  
    //   }
    //   if($wid > 0){
    //     $rs_Ward = DB::select(DB::raw("select * from `ward_villages` where `id` = $wid;"));
    //     // $wardcode = WardVillage::where('id', $wid)->first();
    //     $wcondition = " And `vd`.`ward_id` = $wid ";
    //     if (count($rs_Ward) > 0) {
    //       $rs_Ward = reset($rs_Ward);
    //       $wcodecondition = " And GP_Ward = $rs_Ward->ward_no ";    
    //     }
    //   }

    //   $districts=DB::
    //     select(DB::raw("select `d`.`id`, `s`.`code` as `scode`, `s`.`name_e` as `sname`, `d`.`name_e`, `d`.`code` 
    //       from `districts` `d`
    //       Inner Join `states` `s` on `s`.`id` = `d`.`state_id` $dcondition;"));

    //   foreach ($districts as $district) {
    //     echo "Porting District '$district->name_e'.\n";
    //     if(strlen($district->scode)>3){
    //       die( "length of State Code cannot be more than 3.\n");
    //     }
    //     if(strlen($district->code)>2){
    //       die( "length of District Code cannot be more than 2.\n");
    //     }

    //     $query = "Delete From [District] Where [DisttCode] = '$district->code'"; 
    //     $result = sqlsrv_query($conn,$query);
        
    //     $query = "Insert Into [District] ([State_code], [State_Name], [DisttName], [DisttCode], [flag]) Values ('$district->scode', '$district->sname', '$district->name_e', '$district->code', 'Y')"; 
    //     $result = sqlsrv_query($conn,$query);


    //     $blocks=DB::
    //       select(DB::raw("Select `bl`.`id`, `bl`.`code` as `blcode`, `bl`.`name_l` as `blname_l`, `d`.`code`, `d`.`name_e`, `bl`.`name_e` as `blname_e` From `blocks_mcs` `bl` Inner Join `districts` `d` On `d`.`id` = `bl`.`districts_id` where `bl`.`districts_id` = '$district->id' $bcondition;"));

    //     foreach ($blocks as $block) {
    //       echo "Porting Block '$block->blname_e'.\n";
    //       if(strlen($block->blcode)>5){
    //         die( "length of Block Code cannot be more than 5.\n");
    //       }
          
    //       $query = "Delete From [Block] Where [CODE] = '$block->blcode' And [disttcode] = '$block->code'"; 
    //       $result = sqlsrv_query($conn,$query);

    //       $query = "Insert Into [Block] ([CODE], [desc1], [distt], [disttcode], [NAme_eng]) Values ('$block->blcode', N'$block->blname_l', '$block->name_e', '$block->code', '$block->blname_e')"; 
    //       $result = sqlsrv_query($conn,$query);


    //       $villages=DB::
    //         select(DB::raw("select `vl`.`id`, `bl`.`code` as `blcode`, `vl`.`code` as `vlcode`, `vl`.`name_e`, `vl`.`name_l`, `d`.`code` as `dcode` from `villages` `vl` inner join `blocks_mcs` `bl` on `bl`.`id` = `vl`.`blocks_id` inner join `districts` `d` on `d`.`id` = `vl`.`districts_id` where `vl`.`blocks_id` = $block->id $vcondition;"));

    //       foreach ($villages as $village) {
    //         echo "Porting Village '$village->name_e'.\n";
    //         if(strlen($village->vlcode)>5){
    //           die( "length of Panchayat Code cannot be more than 5.\n");
    //         }
            
    //         $query = "Delete From [Panchayat] Where [V_CODE] = '$village->vlcode' And [BLOCK] = '$village->blcode' And [disttcode] = '$village->dcode'"; 
    //         $result = sqlsrv_query($conn,$query);

    //         $query = "Delete From [b02] Where [DisttCode] = '$district->code' And [Block_Code] = '$block->blcode' And [GP_Code] = '$village->vlcode' $wcodecondition"; 
    //         $result = sqlsrv_query($conn,$query);


    //         $query = "Insert Into [Panchayat] ([BLOCK], [V_CODE], [NAME], [HNAME], [PCODE], [disttcode]) Values ('$village->blcode', '$village->vlcode', '$village->name_e', N'$village->name_l', '$village->vlcode', '$village->dcode')"; 
    //         $result = sqlsrv_query($conn,$query);

                        

    //         $voters=DB::
    //         select(DB::raw("select `ac`.`code`, `wv`.`ward_no`, `ap`.`part_no`, `vd`.`sr_no`, `vd`.`house_no_e`,
    //           `vd`.`name_e`, `vd`.`father_name_e`, `vd`.`voter_card_no`, `vd`.`age`, `g`.`code` as `gcode`, `rl`.`code` as `rcode`, `vd`.`name_l`, `vd`.`father_name_l`, `vd`.`house_no`, `vd`.`print_sr_no`, `vd`.`mobile_no`, `pb`.`booth_no`, `pb`.`booth_no_c` 
    //           from `voters` `vd` Inner Join `assemblys` `ac` on `ac`.`id` = `vd`.`assembly_id` inner join `assembly_parts` `ap` on `ap`.`id` =`vd`.`assembly_part_id` Inner Join `relation` `rl` on `rl`.`id` = `vd`.`relation` inner Join `genders` `g` on `g`.`id` = `vd`.`gender_id` inner Join `ward_villages` `wv` on `wv`.`id` = `vd`.`ward_id` Left Join `polling_booths` `pb` on `pb`.`id` = `vd`.`booth_id` Where `vd`.`status` in (0,1,3) And `vd`.`village_id` = $village->id $wcondition;"));
            
    //         foreach ($voters as $voter) {
              
    //           $booth_no = $voter->booth_no.$voter->booth_no_c;

    //           $query = "Insert Into [b02] ([DisttCode], [AC_NO], [Block_Code], [GP_Code], [GP_Ward], [PART_NO], [SLNOINPART], [HOUSE_NO], 
    //           [FM_NAME], [RLN_FM_NM], [IDCARD_NO], [SEX],
    //           [AGE], [status], [name_eng], [fname_eng],
    //           [Rln], [numric_hno], [srn], [mobileno], [uflag], [booth]
    //           ) 
    //             Values ('$district->code', '$voter->code', '$block->blcode', '$village->vlcode',
    //             '$voter->ward_no', '$voter->part_no', '$voter->sr_no', '$voter->house_no_e',
    //             N'$voter->name_l', N'$voter->father_name_l', '$voter->voter_card_no', '$voter->gcode',
    //             '$voter->age', 'O', '$voter->name_e', '$voter->father_name_e',
    //             '$voter->rcode', '$voter->house_no', '$voter->print_sr_no', '$voter->mobile_no', 0, '$booth_no'
    //             )"; 
    //           // echo($query);
    //           // return;
    //           $result = sqlsrv_query($conn,$query);

    //         }

    //         $query = "Delete From [booth] Where [disttcode] = '$district->code' And [block] = '$block->blcode' And [pcode] = '$village->vlcode'"; 
    //         $result = sqlsrv_query($conn,$query);

    //         $rs_polling_booths=DB::
    //         select(DB::raw("select * from `polling_booths` where `village_id` = $village->id;"));
            
    //         foreach ($rs_polling_booths as $polling_booths) {
    //           $booth_no = $polling_booths->booth_no.$polling_booths->booth_no_c;
    //           $query = "Insert Into [booth] ([disttcode], [block], [pcode], [boothno], [boothname]) Values ('$district->code', '$block->blcode', '$village->vlcode', '$booth_no', '$polling_booths->name_e')"; 
    //           $result = sqlsrv_query($conn,$query);

    //         }





    //       }


    //     }


    //   }

    //   echo "Porting Successfully.".date("d-m-y H:i:s")."\n";

    //   sqlsrv_close($conn);
      
       
    //  }     
       


  //Only Panchayat
    public function handle()
    {   
      echo "OK\n";
      $serverName="10.145.121.9";
      $database="left_panchayat";
      $username=getenv('sa');
      $passward=getenv('Nicjjr@123'); 
      // $serverName =$serverName;
      // $options = array(  "UID" =>$username,  "PWD" =>$passward,  "Database" =>$database, "CharacterSet" => "UTF-8");
      // $conn = sqlsrv_connect($serverName, $options); 
      // if( $conn === false )
      // {
      // echo "Could not connect.\n";
      // die( print_r( sqlsrv_errors(), true));
      // }
      
      echo "Porting Stared.".date("d-m-y H:i:s")."\n";

      $did = $this->argument('district_id');
      $bid = $this->argument('block_id');
      $vid = $this->argument('village_id');
      $wid = $this->argument('ward_id');
      // echo $did."\n";
      // echo $this->argument('district_id')."\n";
      // die("ok");
      $dcondition = "";
      $bcondition = "";
      $vcondition = "";
      $wcondition = "";
      $wcodecondition = "";
      if($did > 0){
        $dcondition = " Where `d`.`id` = $did ";  
      }
      if($bid > 0){
        $bcondition = " And `bl`.`id` = $bid ";  
      }
      if($vid > 0){
        $vcondition = " And `vl`.`id` = $vid ";  
      }
      if($wid > 0){
        $rs_Ward = DB::select(DB::raw("select * from `ward_villages` where `id` = $wid;"));
        // $wardcode = WardVillage::where('id', $wid)->first();
        $wcondition = " And `vd`.`ward_id` = $wid ";
        if (count($rs_Ward) > 0) {
          $rs_Ward = reset($rs_Ward);
          $wcodecondition = " And GP_Ward = $rs_Ward->ward_no ";    
        }
      }

      $districts=DB::
        select(DB::raw("select `d`.`id`, `s`.`code` as `scode`, `s`.`name_e` as `sname`, `d`.`name_e`, `d`.`code` 
          from `districts` `d`
          Inner Join `states` `s` on `s`.`id` = `d`.`state_id` $dcondition;"));

      foreach ($districts as $district) {
        echo "Porting District '$district->name_e'.\n";
        if(strlen($district->scode)>3){
          die( "length of State Code cannot be more than 3.\n");
        }
        if(strlen($district->code)>2){
          die( "length of District Code cannot be more than 2.\n");
        }

        $blocks=DB::
          select(DB::raw("Select `bl`.`id`, `bl`.`code` as `blcode`, `bl`.`name_l` as `blname_l`, `d`.`code`, `d`.`name_e`, `bl`.`name_e` as `blname_e` From `blocks_mcs` `bl` Inner Join `districts` `d` On `d`.`id` = `bl`.`districts_id` where `bl`.`districts_id` = '$district->id' $bcondition;"));

        foreach ($blocks as $block) {
          echo "Porting Block '$block->blname_e'.\n";
          if(strlen($block->blcode)>5){
            die( "length of Block Code cannot be more than 5.\n");
          }
          
          $villages=DB::
            select(DB::raw("select `vl`.`id`, `bl`.`code` as `blcode`, `vl`.`code` as `vlcode`, `vl`.`name_e`, `vl`.`name_l`, `d`.`code` as `dcode` from `villages` `vl` inner join `blocks_mcs` `bl` on `bl`.`id` = `vl`.`blocks_id` inner join `districts` `d` on `d`.`id` = `vl`.`districts_id` where `vl`.`blocks_id` = $block->id $vcondition;"));

          foreach ($villages as $village) {
            echo "Porting Village '$village->name_e'.\n";
            if(strlen($village->vlcode)>5){
              die( "length of Panchayat Code cannot be more than 5.\n");
            }
            
            $query = "Delete From [Panchayat] Where [V_CODE] = '$village->vlcode' And [BLOCK] = '$village->blcode' And [disttcode] = '$village->dcode'"; 
            // $result = sqlsrv_query($conn,$query);
            $rs_ac_list = DB::connection('sqlsrv')->delete($query);

            $query = "Insert Into [Panchayat] ([BLOCK], [V_CODE], [NAME], [HNAME], [PCODE], [disttcode]) Values ('$village->blcode', '$village->vlcode', '$village->name_e', N'$village->name_l', '$village->vlcode', '$village->dcode')"; 
            // $result = sqlsrv_query($conn,$query);
            $rs_ac_list = DB::connection('sqlsrv')->insert($query);


          }


        }


      }

      echo "Porting Successfully.".date("d-m-y H:i:s")."\n";

      // sqlsrv_close($conn);
      
       
    }       
}
