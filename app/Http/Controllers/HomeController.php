<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Model\Voter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
    //  */
    public function __construct()
    {
       // $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Mail::send(new SendMail('email@ashok.com','otp'));
        // Mail::send('mail',['name','ashok'],function($message){
        //     $message->to('ashok@gmail.com','To Ashok')->subject('test email');
        //     $message->from('ashok@gmail.com','Ashoka');
        // } );
        // $data = array( 'email' => 'sample@sample.com', 'otp' => 'Lar', 'from' => 'sample@sample.comt', 'from_name' => 'Vel' );
        // Mail::send( 'mail', $data, function( $message ) use ($data)
        // {
        //     $message->to( $data['email'] )->from( $data['from'], $data['otp'] )->subject( 'Welcome!' );
        // });
        // return 'done';
        return view('front.registration.dashboard');
        // $classes = array_pluck(ClassType::get(['id','alias'])->toArray(),'alias', 'id');    
        // $sessions = array_pluck(SessionDate::get(['id','date'])->toArray(),'date', 'id');
        // $genders = array_pluck(Gender::get(['id','genders'])->toArray(),'genders', 'id');
        // $religions = array_pluck(Religion::get(['id','name'])->toArray(),'name', 'id');
        // $categories = array_pluck(Category::get(['id','name'])->toArray(),'name', 'id');
        // $default = StudentDefaultValue::find(1); 
           
        // return view('front.registration.form',compact('classes','sessions','default','genders','religions','categories'));
    }

    public function inbox(Request $request){  
          Log::info($request);
        // $inbox =new Inbox();
        // $inbox->phone =$request->PhNO;
        // $inbox->key_no =$request->Key;
        // $inbox->phrase =$request->Phrase;
        // $inbox->param =$request->Param;
        // $inbox->full_msg =$request->FullMsg;
    }

    public function saveapi()
           { 
             // $ch = curl_init();
             // curl_setopt($ch, CURLOPT_URL, "http://localhost:8000/getdata");
             // // SSL important
             // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
             // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
             // $output = curl_exec($ch); 
             // curl_close($ch);

             // $datas=json_decode($output);
             // dd($datas);
             $ipinfoAPI="http://localhost:8000/getdata";
            $json =file_get_contents($ipinfoAPI);
            $data= (array) json_decode($json);
            
             foreach ($datas as $data) {
               $Voter =new Voter();
               $Voter->name_e =$data->name;
               $Voter->mobile_no =$data->mobile;
               $Voter->save();
             }
            
               $phonebooks =Phonebook::get();
             return view('ayush',compact('phonebooks'));
           }
}