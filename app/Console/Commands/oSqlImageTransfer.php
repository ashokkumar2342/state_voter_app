<?php

namespace App\Console\Commands;

use App\Admin;
use Illuminate\Support\Facades\DB;

use Illuminate\Console\Command;

class oSqlImageTransfer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Sqlimage:transfer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sqlimage Transfer ';

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
        ini_set('max_execution_time', '14400');
        ini_set('memory_limit','999M');
        ini_set("pcre.backtrack_limit", "5000000");

        
         

        $ACPARTS = DB::connection('sqlsrv')->select("Select Distinct Ac_No, Part_No from data Order By AC_NO, Part_No");
        
        foreach ($ACPARTS as $key => $ac_part) {
            // $dirpath = Storage_path() . '/app/imageall/'.$ac_part->Ac_No.'/'.$ac_part->Part_No;
            // $vpath = '/imageall/'.$ac_part->Ac_No.'/'.$ac_part->Part_No;
            // @mkdir($dirpath, 0755, true);

            $dirpath = 'D:/imageall/'.$ac_part->Ac_No.'/'.$ac_part->Part_No;
            $vpath = '/'.$ac_part->Ac_No.'/'.$ac_part->Part_No;
            @mkdir($dirpath, 0755, true);

            $datas = DB::connection('sqlsrv')->select("select SlNoInPart, PHOTO from data where ac_no =$ac_part->Ac_No and part_no =$ac_part->Part_No order by SlNoInPart");
            
            foreach ($datas as $key => $value) { 
                $image=$value->PHOTO;
                $name =$value->SlNoInPart;
                // $image= \Storage::disk('local')->put($vpath.'/'.$name.'.jpg', $image);
                $image= \Storage::disk('uploads')->put($vpath.'/'.$name.'.jpg', $image);
            }
        }   
    }
}
