<?php

namespace App\Base\Helpers;
use App\Base\Helpers\NepaliCalendar;
use Carbon\Carbon;


class GetNepaliServerDate
{
    // Function to replace English Numbers to Nepali
    function convertToNepaliNumber($input) {
    
        $standard_numsets = array("0","1","2","3","4","5","6","7","8","9");
        $devanagari_numsets = array("०","१","२","३","४","५","६","७","८","९");
        
        return str_replace($standard_numsets, $devanagari_numsets, $input);
      }

      // Function that actually converts and implode the nepali numbers
      function getNepaliNum($num){
        $input = str_split($num);
        $output = '';
        $output_arr = GetNepaliServerDate::convertToNepaliNumber($input);
        $output = implode('', $output_arr);

        return $output;
    }
    
    // Function to split the full english date to year, month and date
    function splitDate()
    {
     $currentDate = Carbon::now()->toDateString();

     $currentDate = explode('-', $currentDate);

     $year  = $currentDate[0];
     $month = $currentDate[1];
     $date   = $currentDate[2];

     $split_date =[
         'year' => $year,
         'month' =>$month,
         'date' => $date ]; 

     return $split_date;       
    }

    // Function that actually converts server english date to corresponding nepali date 
    function getNepaliDate(){
        $dateHelper = new GetNepaliServerDate();

        $e_year = $dateHelper->splitDate()['year'];
        $e_month = $dateHelper->splitDate()['month'];
        $e_date = $dateHelper->splitDate()['date'];

        $cal = new NepaliCalendar();     //For calling eng_to_nep function in NepaliCalendar class
        // $nepali_Date = $cal->eng_to_nep($e_year, $e_month, $e_date);
        $nepali_Date = $cal->eng_to_nep($e_year, $e_month, $e_date);

        $nepaliDate =implode("-", $nepali_Date);

        // To get year and date in nepali number 

      
        // $n_year = $nepali_Date['year'];
        $nepali_year = $nepali_Date['year'];
        // $nepali_year = GetNepaliServerDate::getNepaliNum($n_year);

        // $n_date = $nepali_Date['date'];
        $nepali_date = $nepali_Date['date'];
        // $nepali_date = GetNepaliServerDate::getNepaliNum($n_date);

        $nepali_day =$nepali_Date['day'];
        $nepali_month =$nepali_Date['nmonth'];

        $nepali_date = [$nepali_year, $nepali_month, $nepali_date];
        $nepali_date = implode(" - ",$nepali_date);
        $nepali_date_full = [$nepali_date, $nepali_day];      
        $nepaliDateFull = implode(", ",$nepali_date_full);

   
        // return $nepali_year;
        // return $nepali_date;
        // return $nepali_month;
        // return $nepaliDate; 
        return $nepaliDateFull;
    }
}