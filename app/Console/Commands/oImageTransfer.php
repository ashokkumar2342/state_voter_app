<?php

namespace App\Console\Commands;

use App\Admin;
use App\Events\SmsEvent;
use App\Helpers\MailHelper;
use App\Jobs\SendSmsJob;
use App\Model\Sms\EmailTemplate;
use App\Model\Sms\SentEmailAttachment;
use App\Model\Sms\SentEmailDetail;
use App\Model\Sms\SentSmsDetail;
use App\Model\Voter;
use App\Model\Assembly;
use App\Model\AssemblyPart;
use Illuminate\Console\Command;

class oImageTransfer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:transfer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Image Transfer ';

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
        ini_set("pcre.backtrack_limit", "5000000");
        
        $assembly = Assembly::where('id','>',2)->get();
        foreach ($assembly as $key => $ac_id) {
            $ac_parts = AssemblyPart::where('assembly_id',$ac_id->id)->get();
            foreach ($ac_parts as $key => $ac_part_id) {
                
                $voterimage = Voter::where('assembly_id',$ac_id->id)->where('assembly_part_id',$ac_part_id->id)->get();

                $dirpath = Storage_path() . '/app/vimage/'.$ac_id->id.'/'.$ac_part_id->id;
                $vpath = '/vimage/'.$ac_id->id.'/'.$ac_part_id->id;
                @mkdir($dirpath, 0755, true);

                foreach ($voterimage as $key => $img_id) {
                    $image=$img_id->image;
                    $name =$img_id->id;
                    $image= \Storage::disk('local')->put($vpath.'/'.$name.'.jpg', $image);        
                }

            }
        }
    }
}
