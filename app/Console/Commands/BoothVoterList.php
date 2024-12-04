<?php

namespace App\Console\Commands;

use App\Admin;
use App\Helper\MyFuncs;
// use App\Model\Assembly;
// use App\Model\AssemblyPart;
// use App\Model\BlocksMc;
// use App\Model\DefaultValue;
// use App\Model\History;
// use App\Model\Village;
// use App\Model\Voter;
// use App\Model\VoterImage;
// use App\Model\VoterListMaster;
// use App\Model\VoterListProcessed;
// use App\Model\WardVillage;
// use App\Model\MainPageDetails;
// use App\Model\PollingBooth;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BoothVoterList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'boothvotelist:generate {district_id} {block_id} {booth_id}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'boothvotelist generate';

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
    ini_set("pcre.backtrack_limit", "100000000");
    $district_id = $this->argument('district_id');
    $block_id = $this->argument('block_id');  
    $booth_id = $this->argument('booth_id');

    


    $voterListMaster = DB::select(DB::raw("select * from `voter_list_master` where `status` = 1 and `block_id` = $block_id limit 1;"));
    $voterListMaster = reset($voterListMaster); 
    $voter_list_id = $voterListMaster->id;

    // $processMainPage = DB::select(DB::raw(" call `up_print_voterlist_booth`($booth_id);"));

    $VoterListProcessed=DB::select(DB::raw("Select * From `booth_voter_list` where `booth_id` = $booth_id;"));
    
    $rs_result = DB::select(DB::raw("select concat(`pb`.`booth_no`, ifnull(`pb`.`booth_no_c`, '')) as `booth`, `vil`.`name_e` from `booth_voter_list` `bvl` inner join `polling_booths` `pb` on `pb`.`id` = `bvl`.`booth_id` inner join `villages` `vil` on `vil`.`id` = `pb`.`village_id` where `bvl`.`booth_id` = $booth_id;"));

    $village_booth = $rs_result[0]->name_e. ' - '.$rs_result[0]->booth;

    $newId=DB::select(DB::raw("Update `booth_voter_list` set `status` = 2 where `booth_id` = $booth_id;"));

    echo "Processing Booth Voter List :: ".$village_booth. "\n";

    $dirpath = Storage_path() . $VoterListProcessed[0]->folder_path;
    @mkdir($dirpath, 0755, true);
    chmod($dirpath, 0755);

    $path=Storage_path('fonts/');
    $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir']; 
    $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];

    $mpdf_photo = new \Mpdf\Mpdf([
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
         ]);

    $mpdf_photo_wop = new \Mpdf\Mpdf([
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
         ]);

    $pagetype=3;
    $WardVillages=DB::select(DB::raw("select distinct `ward_id` from `voters` where `booth_id` = $booth_id order by `ward_id`;")); 
      
    $html = view('admin.master.PrepareVoterList.voter_list_section.start_pdf');

    $html = $html.'</style></head><body>';

    
    $mpdf_photo->WriteHTML($html);
    $mpdf_photo_wop->WriteHTML($html);
    
    
    $wardcount = 1;
    $totalpage=0;
    foreach ($WardVillages as $key => $WardVillage) {
        if ($wardcount>1){
            $mpdf_photo->WriteHTML('<pagebreak>');
            $mpdf_photo_wop->WriteHTML('<pagebreak>');
            if(fmod($totalpage, 2)==1){
                $mpdf_photo->WriteHTML('<pagebreak>');
                $mpdf_photo_wop->WriteHTML('<pagebreak>');
            }    
        }
        $wardcount++;

        $booth_condition = " And `v`.`booth_id` = $booth_id";

        $rsDataListRemarks = DB::select(DB::raw("select * from `import_type` where `id` in (select distinct `data_list_id` from `voters` `v` where `v`.`ward_id` = $WardVillage->ward_id And `v`.`status` <> 2 $booth_condition);"));

        $voterReports = DB::select(DB::raw("select `v`.`id`, `v`.`assembly_id`, `v`.`assembly_part_id`, `v`.`print_sr_no`, `v`.`voter_card_no`, case `source` when 'V' then concat('*', `v`.`sr_no`, '/', `ap`.`part_no`) Else 'New' End as `part_srno`, `v`.`name_l`, `r`.`relation_l` as `vrelation`, `v`.`father_name_l`, `v`.`house_no_l`, `v`.`age`, `g`.`genders_l`, `v`.`data_list_id`, `v`.`sr_no` From `voters` `v` inner join `assembly_parts` `ap` on `ap`.`id` = `v`.`assembly_part_id` Inner Join `genders` `g` on `g`.`id` = `v`.`gender_id` Inner Join `relation` `r` on `r`.`id` = `v`.`relation` where `v`.`ward_id` = $WardVillage->ward_id And `v`.`status` in (0,1,3) $booth_condition Order By `v`.`print_sr_no`;"));
        
        

        $mainpagedetails_rs = DB::select(DB::raw("Select * From `main_page_detail` where `voter_list_master_id` = $voter_list_id and `ward_id` = $WardVillage->ward_id and `booth_id` = $booth_id;"));
        $mainpagedetails=count($mainpagedetails_rs);

        if ($mainpagedetails>0){
            $mainpagedetails= DB::select(DB::raw("Select * From `main_page_detail` where `voter_list_master_id` = $voter_list_id and `ward_id` =$WardVillage->ward_id and `booth_id` = $booth_id;"));
            
            $voterssrnodetails = DB::select(DB::raw("Select * From `voters_srno_detail` where `id` = 0;"));

            $block_type = DB::select(DB::raw("select * From `blocks_mcs` where `id` = $block_id;"));

            $main_page_type = 0;
            if ($block_type[0]->block_mc_type_id == 1){
                $main_page_type = 1;    
            }else{
                $main_page_type = 2;
            }
            
            $votercount = count($voterReports);
            $totalpage = (int)($votercount/30);
            if ($totalpage*30<$votercount){$totalpage++;}
            $totalpage++;
            $main_page=$this->prepareMainPage($mainpagedetails, $voterssrnodetails, $totalpage, $main_page_type, 0, $rsDataListRemarks);
            $mpdf_photo->WriteHTML($main_page);
            $mpdf_photo_wop->WriteHTML($main_page);
            
            $voter_per_page = 30;
            $SuchiType = '';
            $PrintedRows = 0;
            $cpageno = 1;
        
            $printphoto = 1;
            $main_page=$this->prepareVoterDetail($voterReports, $mainpagedetails, $totalpage, $printphoto, $voter_per_page, $SuchiType, $PrintedRows, $cpageno, $rsDataListRemarks);
            $mpdf_photo->WriteHTML($main_page);

            $printphoto = 0;
            $main_page=$this->prepareVoterDetail($voterReports, $mainpagedetails, $totalpage, $printphoto, $voter_per_page, $SuchiType, $PrintedRows, $cpageno, $rsDataListRemarks);
            $mpdf_photo_wop->WriteHTML($main_page);
        
        }
    }

    $mpdf_photo->WriteHTML('</body></html>');
    $mpdf_photo_wop->WriteHTML('</body></html>');
    
    
    $filepath = Storage_path() . $VoterListProcessed[0]->folder_path . $VoterListProcessed[0]->file_name;
    $mpdf_photo->Output($filepath, 'F');
    chmod($filepath, 0755);

    $filepath = Storage_path() . $VoterListProcessed[0]->folder_path . $VoterListProcessed[0]->file_name_w;
    $mpdf_photo_wop->Output($filepath, 'F');
    chmod($filepath, 0755);

    $newId=DB::select(DB::raw("Update `booth_voter_list` set `status` = 1 where `booth_id` = $booth_id;"));
    
    }


    
    public function prepareVoterDetail($voterReports, $mainpagedetails, $totalpage, $printphoto, $voter_per_page, $SuchiType, $PrintedRows, $cpageno, $rsDataListRemarks)
    {
        
        return $main_page=view('admin.master.PrepareVoterList.voter_list_section.voter_detail',compact('voterReports', 'mainpagedetails', 'totalpage', 'printphoto', 'voter_per_page', 'SuchiType', 'PrintedRows', 'cpageno', 'rsDataListRemarks'));    
    }

    
    public function prepareMainPage($mainpagedetails, $voterssrnodetails, $totalpage, $main_page_type, $is_suppliment, $rsDataListRemarks)
    {
        $showsrnotext = 0;
        return $main_page=view('admin.master.PrepareVoterList.voter_list_section.main_page',compact('mainpagedetails','voterssrnodetails', 'totalpage', 'main_page_type', 'is_suppliment', 'rsDataListRemarks', 'showsrnotext'));    
    }
           
}
