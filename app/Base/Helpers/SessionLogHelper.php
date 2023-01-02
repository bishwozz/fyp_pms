<?php

namespace App\Base\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Jenssegers\Agent\Agent;

class SessionLogHelper
{
    public function getIpAddressofClient()                  //Get client IP Address
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';    
        return $ipaddress;
    }

    public function getDevice()                            // detect device like desktop, mobile
    {
        $agent = new Agent();
        if($agent->isDesktop()){                
            $device = "Desktop";
        }elseif($agent->isMobile()){
            $device = "Mobile"; 
        }
        
        return $device;
    } 

    public function getPlatform()                   //detect platfrom like Windows, Mac OS, Ubuntu
    {
        $agent = new Agent();
        $platform = $agent->platform();    
        $platform_version = $agent->version($platform);
        
        return "Platform: ".$platform . " , " . "Version: " .$platform_version ;
    }
    
    public function getBrowser()                   // detect browser like Chrome, Firefox, Mozilla 
    {
        $agent = new Agent();
        $browser = $agent->browser();      
        $browser_version = $agent->version($browser);

        return "Browser: ".$browser . " , " . "Version: " .$browser_version ;
    }
    
    function getClientMacAddress()                      // Get Client MAC Address
    {
        return substr(exec('getmac'), 0,17); 
    }
}
