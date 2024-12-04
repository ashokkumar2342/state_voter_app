<?php

namespace App\Console\Commands;

use App\Admin;
use App\Helper\MyFuncs;
use App\Model\Assembly;
use App\Model\AssemblyPart;
use App\Model\BlocksMc;
use App\Model\DefaultValue;
use App\Model\History;
use App\Model\Village;
use App\Model\Voter;
use App\Model\VoterImage;
use App\Model\VoterListMaster;
use App\Model\VoterListProcessed;
use App\Model\WardVillage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class oVoterListGeneratePanchayat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voterlistpanchayat:generate {district_id} {block_id} {village_id}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'voterlistpanchayat generate';

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
    //\Log::info(date('Y-m-d H:i:s'));
    public function handle()
    { 
      ini_set('max_execution_time', '3600');
      ini_set('memory_limit','999M');
      ini_set("pcre.backtrack_limit", "5000000");
     $district_id = $this->argument('district_id');
     $block_id = $this->argument('block_id'); 
     $village_id = $this->argument('village_id'); 
      

     $voterListMaster=VoterListMaster::where('status',1)->first(); 
     $blockcode=BlocksMc::find($block_id); 
     $villagename=Village::find($village_id);
     $WardVillages= WardVillage::where('village_id',$village_id)->get();
      //  $PrepareVoterListSave= DB::select(DB::raw("call up_process_voterlist ('1')"));  
      //  $mainpagedetails= DB::select(DB::raw("Select * From `main_page_detail` where `voter_list_master_id` =$voterListMaster->id and `ward_id` =1;")); 
      //  $voterssrnodetails = DB::select(DB::raw("Select * From `voters_srno_detail` where `voter_list_master_id` =$voterListMaster->id and `wardid` =1;"));
      // $voterReports = DB::select(DB::raw("select `v`.`print_sr_no`, `v`.`voter_card_no`, case `source` when 'V' then concat('*', `ap`.`part_no`, '/', `v`.`sr_no`) Else 'New' End as `part_srno`, `v`.`name_l`, `r`.`relation_l` as `vrelation`, `v`.`father_name_l`, `v`.`house_no_l`, `v`.`age`, `g`.`genders_l`, `vi`.`image` From `voters` `v` inner join `assembly_parts` `ap` on `ap`.`id` = `v`.`assembly_part_id` Inner Join `genders` `g` on `g`.`id` = `v`.`gender_id` Inner Join `voter_image` `vi` on `vi`.`voter_id` = `v`.`id` Inner Join `relation` `r` on `r`.`id` = `v`.`relation` where `v`.`ward_id` = 206 And `v`.`status` in (0,1,3) Order By `v`.`print_sr_no`;")); 
        
        $path=Storage_path('fonts/');
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir']; 
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata']; 
         $mpdf = new \Mpdf\Mpdf([
             'fontDir' => array_merge($fontDirs, [
                 __DIR__ . $path,
             ]),
             'fontdata' => $fontData + [
                 'frutiger' => [
                     'R' => 'FreeSans.ttf',
                     'I' => 'FreeSansOblique.ttf',
                 ]
             ],
             'default_font' => 'freesans',
             'pagenumPrefix' => '',
            'pagenumSuffix' => '',
            'nbpgPrefix' => ' कुल ',
            'nbpgSuffix' => ' पृष्ठों का पृष्ठ'
         ]); 
         $html = view('admin.master.PrepareVoterList.panchayat_report_p',compact('mainpagedetails','voterssrnodetails','voterReports','WardVillages')); 
         $mpdf->WriteHTML($html); 
         $documentUrl = Storage_path() . '/app/voter/'.$voterListMaster->id.'/'.'panchayat';   
        @mkdir($documentUrl, 0755, true);  
        $mpdf->Output($documentUrl.'/'.'p_'.$blockcode->code.'_'.$villagename->name_e.'.pdf', 'F');
        $voterlistprocessed=VoterListProcessed::where('voter_list_master_id',$voterListMaster->id)->where('district_id',$district_id)->where('block_id',$block_id)->where('village_id',$village_id)->where('report_type','panchayat')->where('status',0)->first(); 
        $voterlistprocessed->file_path_p='app/voter/'.$voterListMaster->id.'/'.'panchayat'.'/'.'p_'.$blockcode->code.'_'.$villagename->name_e.'.pdf'; 
        $voterlistprocessed->save(); 
        return  $this->SaveMunicipal($district_id,$block_id,$village_id); 
     }
     public function SaveMunicipal($district,$block,$village)
    {  
        $voterListMaster=VoterListMaster::where('status',1)->first(); 
        $blockcode=BlocksMc::find($block);
        $villagename=Village::find($village);
        $WardVillages= WardVillage::where('village_id',$village)->get(); 
        $path=Storage_path('fonts/');
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir']; 
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata']; 
         $mpdf = new \Mpdf\Mpdf([
             'fontDir' => array_merge($fontDirs, [
                 __DIR__ . $path,
             ]),
             'fontdata' => $fontData + [
                 'frutiger' => [
                     'R' => 'FreeSans.ttf',
                     'I' => 'FreeSansOblique.ttf',
                 ]
             ],
             'default_font' => 'freesans',
             'pagenumPrefix' => '',
            'pagenumSuffix' => '',
            'nbpgPrefix' => ' कुल ',
            'nbpgSuffix' => ' पृष्ठों का पृष्ठ'
         ]); 
         $html = view('admin.master.PrepareVoterList.report_without_photo',compact('WardVillages')); 
         $mpdf->WriteHTML($html); 
         $documentUrl = Storage_path() . '/app/voter/'.$voterListMaster->id.'/'.'panchayat';   
        @mkdir($documentUrl, 0755, true);  
        $mpdf->Output($documentUrl.'/'.'w_'.$blockcode->code.'_'.$villagename->name_e.'.pdf', 'F');
        $voterlistprocessed=VoterListProcessed::where('voter_list_master_id',$voterListMaster->id)->where('district_id',$district)->where('block_id',$block)->where('village_id',$village)->where('report_type','panchayat')->where('status',0)->first(); 
        $voterlistprocessed->file_path_w='app/voter/'.$voterListMaster->id.'/'.'panchayat'.'/'.'w_'.$blockcode->code.'_'.$villagename->name_e.'.pdf';
        $voterlistprocessed->prepared_date=date('Y-m-d'); 
        $voterlistprocessed->status=1; 
        $voterlistprocessed->save(); 
          
    }
     
       
}
