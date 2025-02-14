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
// use App\Model\VoterSlipProcessed;
// use App\Model\WardVillage;
// use App\Model\PollingBooth;
// use App\Model\PollingDayTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PrepareVoterSlip extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'preparevoterslip:generate';
    // protected $signature = 'preparevoterslip:generate {district_id} {block_id} {village_id} {ward_id} {booth_id} {slip_per_page}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'voterslip generate';

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
        ini_set('max_execution_time', '28800');
        ini_set('memory_limit','9999M');
        ini_set("pcre.backtrack_limit", "100000000");

        $queue_id = 0;
        while($queue_id == 0){
            $rs_fetch = DB::select(DB::raw("call `up_fetch_id_for_voterSlipGenerate`();"));
            $queue_id = $rs_fetch[0]->queue_id;
            if($queue_id == 0){
                sleep(30);
            }
        }

        while ($queue_id > 0){
            $rs_VoterListProcessed = DB::select(DB::raw("SELECT * from `voter_slip_processed` where `id` = $queue_id limit 1;"));            

            $district_id = $rs_VoterListProcessed[0]->district_id;
            $block_id = $rs_VoterListProcessed[0]->block_id; 
            $village_id = $rs_VoterListProcessed[0]->village_id; 
            $ward_id = $rs_VoterListProcessed[0]->ward_id;
            $booth_id = $rs_VoterListProcessed[0]->booth_id;
            $slip_per_page = $rs_VoterListProcessed[0]->slip_per_page;

            $rs_result = DB::select(DB::raw("SELECT * from `villages` where `id` = $village_id limit 1;"));
            $village_name_e = $rs_result[0]->name_e;

            $VoterSlipProcessed = DB::select(DB::raw("SELECT * from `voter_slip_processed` where `id` = $queue_id limit 1;"));
            $VoterSlipProcessed = reset($VoterSlipProcessed);

            $dirpath = Storage_path() . $VoterSlipProcessed->folder_path;
            @mkdir($dirpath, 0755, true);
            chmod($dirpath, 0755);

            $path=Storage_path('fonts/');
            $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir']; 
            $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata']; 
            $mpdf_slip = new \Mpdf\Mpdf([
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

            $voterListMaster = DB::select(DB::raw("SELECT * from `voter_list_master` where `block_id` = $block_id and `status` = 1 limit 1;"));

            $blockMcs = DB::select(DB::raw("SELECT * from `blocks_mcs` where `id` = $block_id limit 1;"));

            if ($blockMcs[0]->block_mc_type_id==1){
                $slipheader = 'पंचायत ('.$blockMcs[0]->name_l.') '.$voterListMaster[0]->remarks2.' - '.$voterListMaster[0]->year_base;
            }else{
                $slipheader = $blockMcs[0]->name_l.' '.$voterListMaster[0]->remarks2.' - '.$voterListMaster[0]->year_base;
            }


            $rs_slip_note = DB::select(DB::raw("SELECT * from `voter_slip_notes` where `district_id` = $district_id order by `note_srno`;"));
            $html = view('admin.master.PrepareVoterList.voter_list_section.start_pdf');
            $html = $html.'</style></head><body>';
            $mpdf_slip->WriteHTML($html);


            $WardVillages = DB::select(DB::raw("SELECT * from `ward_villages` where `id` = $ward_id limit 1;"));

            $wardcount = 1;
            foreach ($WardVillages as $WardVillage) {
                echo "Processing Voter Slip :: ".$village_name_e.' - '.$WardVillage->ward_no." \n";
                if ($wardcount>1){
                    $mpdf_slip->WriteHTML('<pagebreak>');    
                }
                $wardcount++;
                $ward_no = $WardVillage->ward_no;

                if ($booth_id==0){$booth_condition = "";}else{$booth_condition = " And `v`.`booth_id` = $booth_id";}

                // $booth_condition = "";
                $query = "SELECT `v`.`id`, `v`.`assembly_id`, `v`.`assembly_part_id`, `v`.`print_sr_no`, `v`.`voter_card_no`, `ap`.`part_no`, `v`.`name_l`, `r`.`relation_l` as `vrelation`, `v`.`father_name_l`, `g`.`genders_l`, concat(`pb`.`booth_no`, `pb`.`booth_no_c`) as `boothno`, `pb`.`name_l` as `pb_name`, `v`.`data_list_id`, `v`.`sr_no` From `voters` `v` inner join `assembly_parts` `ap` on `ap`.`id` = `v`.`assembly_part_id` Inner Join `genders` `g` on `g`.`id` = `v`.`gender_id` Inner Join `relation` `r` on `r`.`id` = `v`.`relation` left join `polling_booths` `pb` on `pb`.`id` = `v`.`booth_id` where `v`.`ward_id` =$WardVillage->id And `v`.`status` in (0,1,3) $booth_condition Order By `v`.`print_sr_no`;";
                // dd($query);
                $voterReports = DB::select(DB::raw("$query"));
                
                $polldatetime = DB::select(DB::raw("SELECT * from `polling_day_time` where `block_id` = $block_id limit 1;"));

                // echo "Slip Per Page :: ".$slip_per_page."\n";

                $main_page=$this->prepareVoterSlip($voterReports, $ward_no, $polldatetime, $slipheader, $blockMcs, $rs_slip_note, $slip_per_page);
                // echo "Content :: ".$main_page."\n";
                $mpdf_slip->WriteHTML($main_page);
            
            }

            $mpdf_slip->WriteHTML('</body></html>');
            
            
            $filepath = Storage_path() . $VoterSlipProcessed->folder_path .'/'. $VoterSlipProcessed->file_path;
            $mpdf_slip->Output($filepath, 'F');
            chmod($filepath, 0755);

            $inputPdf = $filepath; //."/disable_text/text.pdf";  // Make sure this file exists
            $outputPdf = $filepath; //."/disable_text/text.pdf";

            // Run the Artisan command
            $exitCode = \Artisan::call('pdf:convert', [
                'inputPdf' => $inputPdf,
                'outputPdf' => $outputPdf,
                'unique_id' => $queue_id,
            ]);

            $newId=DB::select(DB::raw("UPDATE `voter_slip_processed` set `status` = 1, `finish_time` = now() where `id` = $queue_id limit 1;"));

            echo "Saving Complete"." \n";

            $queue_id = 0;
            while($queue_id == 0){
                $rs_fetch = DB::select(DB::raw("call `up_fetch_id_for_voterSlipGenerate`();"));
                $queue_id = $rs_fetch[0]->queue_id;
                if($queue_id == 0){
                    sleep(30);
                }
            }
        }
    }

    public function prepareVoterSlip($voterReports, $wardno, $polldatetime, $slipheader, $blockMcs, $slipNotes, $slip_per_page)
    {
        if($slip_per_page == 1){
            return $main_page=view('admin.master.PrepareVoterSlip.slip',compact('voterReports', 'wardno', 'polldatetime', 'slipheader', 'blockMcs', 'slipNotes'));    
        }elseif($slip_per_page == 2){
            return $main_page=view('admin.master.PrepareVoterSlip.slip_per_page_4',compact('voterReports', 'wardno', 'polldatetime', 'slipheader', 'blockMcs', 'slipNotes'));
        }elseif($slip_per_page == 3){
            return $main_page=view('admin.master.PrepareVoterSlip.slip_per_page_10',compact('voterReports', 'wardno', 'polldatetime', 'slipheader', 'blockMcs', 'slipNotes'));
        }
        
    }
       
}


