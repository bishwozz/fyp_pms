<?php
namespace App\Base\Traits;
use Illuminate\Http\Response;
use App\Base\Helpers\SessionLogHelper;
use App\Base\Helpers\SessionActivityLog;

trait ActivityLogTraits
{
   
     protected function setLogs()
     {      
          foreach ($this->activity as $key => $value) 
          {
               if ($value == $this->crud->getActionMethod()) 
               {
                    $this->registerActivity($value);
               }    
          }
     }

     public function registerActivity($value)
     {
          $request = request();
          $activity_name = static::class;
          $activity_type = $value;
          $url = url()->full();
          $description = $request->all();
          $requestMethod = $request->method();
          $queryString = $request->getQueryString();

          $activity = new SessionActivityLog();
          $activity->addActivityLog($activity_name, $activity_type, $url, $description, $requestMethod, $queryString);
     }
     
}