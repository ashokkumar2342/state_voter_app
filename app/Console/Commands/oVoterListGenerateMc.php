<?php

namespace App\Console\Commands;

use App\Admin;
use App\Helper\MyFuncs;
use App\Model\Assembly;
use App\Model\AssemblyPart;
use App\Model\BlocksMc;
use App\Model\DefaultValue;
use App\Model\History;
use App\Model\Voter;
use App\Model\VoterImage;
use App\Model\VoterListMaster;
use App\Model\VoterListProcessed;
use App\Model\WardVillage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class oVoterListGenerateMc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'voterlistmc:generate {district_id} {block_id} {village_id} {ward_id}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'voterlistmc generate';

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
      ini_set('max_execution_time', '300');
      ini_set("pcre.backtrack_limit", "5000000");
     $district_id = $this->argument('district_id');
     $block_id = $this->argument('block_id'); 
     $village_id = $this->argument('village_id'); 
     $ward_id = $this->argument('ward_id');

     $voterListMaster=VoterListMaster::where('status',1)->first(); 
     $blockcode=BlocksMc::find($block_id); 
     $wardno=WardVillage::find($ward_id); 
       $PrepareVoterListSave= DB::select(DB::raw("call up_process_voterlist ('$ward_id')"));  
       $mainpagedetails= DB::select(DB::raw("Select * From `main_page_detail` where `voter_list_master_id` =$voterListMaster->id and `ward_id` =$ward_id;"));

       $voterssrnodetails = DB::select(DB::raw("Select * From `voters_srno_detail` where `voter_list_master_id` =$voterListMaster->id and `wardid` = $ward_id;"));
      $voterReports = DB::select(DB::raw("select `v`.`print_sr_no`, `v`.`voter_card_no`, case `source` when 'V' then concat('*', `ap`.`part_no`, '/', `v`.`sr_no`) Else 'New' End as `part_srno`, `v`.`name_l`, `r`.`relation_l` as `vrelation`, `v`.`father_name_l`, `v`.`house_no_l`, `v`.`age`, `g`.`genders_l`, `v`.`image` From `voters` `v` inner join `assembly_parts` `ap` on `ap`.`id` = `v`.`assembly_part_id` Inner Join `genders` `g` on `g`.`id` = `v`.`gender_id` Inner Join `relation` `r` on `r`.`id` = `v`.`relation` where `v`.`ward_id` =$ward_id And `v`.`status` in (0,1,3) Order By `v`.`print_sr_no`;")); 
         
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
         $html = view('admin.master.PrepareVoterList.municipal.report_with_photo',compact('mainpagedetails','voterssrnodetails','voterReports')); 
         $mpdf->WriteHTML($html); 
         $documentUrl = Storage_path() . '/app/voter/'.$voterListMaster->id.'/'.'mc';   
        @mkdir($documentUrl, 0755, true);  
        $mpdf->Output($documentUrl.'/'.'p_'.$blockcode->name_e.'_'.$wardno->ward_no.'.pdf', 'F'); 
        $voterlistprocessed=VoterListProcessed::where('voter_list_master_id',$voterListMaster->id)->where('district_id',$district_id)->where('block_id',$block_id)->where('village_id',$village_id)->where('ward_id',$ward_id)->where('status',0)->first(); 
        $voterlistprocessed->file_path_p='app/voter/'.$voterListMaster->id.'/'.'mc'.'/'.'p_'.$blockcode->name_e.'_'.$wardno->ward_no.'.pdf'; 
        $voterlistprocessed->save();  
        return  $this->SaveMunicipal($district_id,$block_id,$village_id,$ward_id,$mainpagedetails,$voterssrnodetails,$voterReports); 
     }
     public function SaveMunicipal($district,$block,$village,$ward,$mainpagedetails,$voterssrnodetails,$voterReports)
    {  
        $voterListMaster=VoterListMaster::where('status',1)->first(); 
        $blockcode=BlocksMc::find($block);
        $wardno=WardVillage::find($ward); 
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
         $html = view('admin.master.PrepareVoterList.municipal.report_without_photo',compact('mainpagedetails','voterssrnodetails','voterReports')); 
         $mpdf->WriteHTML($html); 
         $documentUrl = Storage_path() . '/app/voter/'.$voterListMaster->id.'/'.'mc';   
        @mkdir($documentUrl, 0755, true);  
        $mpdf->Output($documentUrl.'/'.'w_'.$blockcode->name_e.'_'.$wardno->ward_no.'.pdf', 'F');
        $voterlistprocessed=VoterListProcessed::where('voter_list_master_id',$voterListMaster->id)->where('district_id',$district)->where('block_id',$block)->where('village_id',$village)->where('ward_id',$ward)->where('status',0)->first(); 
        $voterlistprocessed->file_path_w='app/voter/'.$voterListMaster->id.'/'.'mc'.'/'.'w_'.$blockcode->name_e.'_'.$wardno->ward_no.'.pdf'; 
        $voterlistprocessed->prepared_date=date('Y-m-d'); 
        $voterlistprocessed->status=1; 
        $voterlistprocessed->save();  
          
    }
     
       
}
