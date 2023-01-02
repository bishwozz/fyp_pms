<?php

namespace App\Base\Helpers;

use DB;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Log\SessionLog;
use App\Models\Log\ActivityLog;
use App\Base\Helpers\SessionLogHelper;
use App\Base\Helpers\GetNepaliServerDate;
use Symfony\Component\HttpFoundation\Response;

class SessionActivityLog extends Response
{
     public static function englishToNepali($input){
          $standard_numsets = array("0","1","2","3","4","5","6","7","8","9",".","-",'am','pm');
          $devanagari_numsets = array("०","१","२","३","४","५","६","७","८","९","|",'-',' बिहान',' दिउँसो');
          
          return str_replace($standard_numsets, $devanagari_numsets, $input);
     }

     public function addSessionLog($session_id, $session_name, $is_currently_logged_in){
          // dd($session_id);
          $data = new SessionLogHelper();          
          $ip = $data->getIpAddressofClient();            
          $device = $data->getDevice();                      
          $platform = $data->getPlatform();
          $browser = $data->getBrowser();
          $macAddress = $data->getClientMacAddress();  
          // dd($ip,$device, $platform,  $browser, $macAddress);
 
          $user = backpack_user();   // to get the user 

          $userId = $user->id;     
          $userName = $user->name;
          $userEmail = $user->email;
          // dd($userId);
          //get date and  time of login
          $englishdate = Carbon::now()->toDateString();
          $nepali_date =new GetNepaliServerDate();
          $nepalidate = $nepali_date->getNepaliDate();
          $time = date("h:i:sa");

          $time = $this->englishToNepali($time);

          $log = [];
          $log['user_id'] =  $userId;
          $log['username'] = $userName;
          $log['user_email'] = $userEmail;
          $log['login_date'] = $nepalidate;
          $log['login_time'] = $time;
          $log['is_currently_logged_in'] = $is_currently_logged_in;
          $log['session_history_id'] = $session_id ;
          $log['session_name'] =  $session_name;
          $log['user_ip'] = $ip ;
          $log['device'] = $device;
          $log['platform'] = $platform;
          $log['browser'] =  $browser;
          $log['mac_address'] = $macAddress;
          $log['created_by'] = $userId;       

          // dd($log);

          return SessionLog::create($log);
     }

     public function addActivityLog($activity_name, $activity_type, $url, $description, $requestMethod, $queryString){
          $englishdate = Carbon::now()->toDateString();
          $nepali_date =new GetNepaliServerDate();
          $nepalidate =$nepali_date->getNepaliDate();
          $time = date("h:i:sa");

          $user = backpack_user();   // to get the user 
          // $userId = $user->id;

          $userId = $user->id;
          // dd($userId);
         
          $currentSessionId = session()->get('sessionId');    // get sesssionId that was put by us during login 

          $response =  new Response();                           // get statusCode and statusText from response
          $statusCode = $response->statusCode; 
          $statusText = $response->statusText;
          $status = $statusCode." : ".$statusText; 

          $log = [];
          $log['session_id'] = $currentSessionId ;
          $log['activity_name'] = $activity_name ;
          $log['activity_type'] = $activity_type ;
          $log['activity_time'] = $time ;
          $log['activity_date_ad'] = $englishdate ;
          $log['activity_date_bs'] = $nepalidate ;
          $log['description'] = $description;
          $log['url'] = $url;
          $log['request_method'] = $requestMethod;
          $log['url_query_string'] = $queryString;
          $log['url_response'] = "";
          $log['status'] =  $status;
          $log['created_by'] = $userId;  

          // dd($log);

          ActivityLog::create($log);
     }

}