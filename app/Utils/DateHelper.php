<?php
namespace App\Utils;

use Carbon\Carbon;
use App\DateSetting;

class DateHelper
{       
    // public $nepalilDate;

    public function __construct()
    {
        //
    }


    //convert bs to ad
    public function convertAdFromBs($bsDate)
    {
        $dateDetail = $this->getDayMonthYear($bsDate);
        $days_to_be_added = $dateDetail['day'] -1;

        $res = DateSetting::where('year_bs', $dateDetail['year'])
        ->where('month_bs', $dateDetail['month'])->first();

        //ad date
        $dayMonthYearAd = $this->getDayMonthYear($res->date_ad);
        $year_ad = $dayMonthYearAd['year'];
        $month_ad = $dayMonthYearAd['month'];
        $day_ad = $dayMonthYearAd['day'];
        $new_day_ad = $day_ad + $days_to_be_added;

        $noOfDaysAdMonth = $res->days_ad;
        
        if($new_day_ad > $noOfDaysAdMonth)
        {
            $new_day_ad = $new_day_ad - $noOfDaysAdMonth;
            $month_ad++;
            if($month_ad > 12)
            {
                $year_ad++;
                $month_ad = 1;
            }
        }
        
        return $year_ad .'/'. $month_ad .'/'. $new_day_ad;
    }

    //convert ad to bs
    public function convertBsFromAd($englishDate)
    {
        $date = $this->getDayMonthYear($englishDate);
        $input_day_ad = $date['day'];

        $res = DateSetting::where('year_ad', $date['year'])
        ->where('month_ad', $date['month'])->first();
        $noOfDaysBsMonth = $res->days_bs;


        //ad date
        $dayMonthYearAd = $this->getDayMonthYear($res->date_ad);
        
        $day_ad = $dayMonthYearAd['day'];
        
        $days_to_be_added = $input_day_ad - $day_ad;//subtract input day and the initial date
        
        //bs date
        $dayMonthYearBs = $this->getDayMonthYear($res->date_bs);
        $year_bs = $dayMonthYearBs['year'];
        $month_bs = $dayMonthYearBs['month'];
        $day_bs = $dayMonthYearBs['day'];

        $new_day_bs = $day_bs + $days_to_be_added;

        if($new_day_bs < 1){//new_day_bs will be in negative
            if($month_bs == '01'){
                $year_bs--;
                $month_bs = 12;
            }else{
                $month_bs--;
            }
            $res = DateSetting::where('year_bs', $year_bs)
            ->where('month_bs', $month_bs)->first();
            //get no of days of new month
            $newNoOfDaysBs = $res->days_bs;
            $new_day_bs = $newNoOfDaysBs + $new_day_bs;
        } 

          // For month condition
          if(strlen($month_bs) === 1)
          {
              $month_bs = str_pad($month_bs,2,0,STR_PAD_LEFT);
          }else{
              $month_bs = $month_bs;
          }
  
          // For day condition
          if(strlen($new_day_bs) === 1){
              $new_day_bs = str_pad($new_day_bs,2,0,STR_PAD_LEFT);
          }else{
              $new_day_bs = $new_day_bs;
          }
  
          return ($year_bs .'-'. $month_bs .'-'. $new_day_bs);    
    }

    //calculate fiscal year
    public function fiscalYear($nepaliDate)
    {
        $date = $this->getDayMonthYear($nepaliDate);
        $year = $date['year'];

        $preYear = $year - 1;
        $nxtYear = $year + 1;
        if($date['month'] < 4)
        {
            return $preYear.'/'.$year;
        } else {
            return $year.'/'.$nxtYear;
        }
    }    

    //explode date
    public function getDayMonthYear($date)
    {
        if(strpos($date,'-') !== false){
            $result_date = explode('-', $date);
        }else{
            $result_date = explode('/', $date);
        }
        
        $year = $result_date[0];
        $month = $result_date[1];
        $day = $result_date[2];
        
        $data['year'] = $year;
        $data['month'] = $month;
        $data['day'] = $day;
        return $data;
    }

    //get data from table
    public function getDateTable($year, $month)
    {
        $dateTable = DateSetting::where('year_bs', $year)->where('month_bs', $month)->first();
        return $dateTable;
    }

    //get total days and months
    public function totalMonthAndDay($start, $end)
    {
        $startAd = $this->convertAdFromBs($start);
        $endAd = $this->convertAdFromBs($end);

        $total['total_days'] = Carbon::parse($startAd)->diffInDays($endAd);        
        $total['total_months'] = Carbon::parse($startAd)->diffInMonths($endAd);
        $total['total_years'] = Carbon::parse($startAd)->diffInYears($endAd);
        
        return $total;
    }

    //date difference
    public function getDifference($start, $end)
    {
        $s = Carbon::parse($start);
        $e = Carbon::parse($end);
        if($s > $e)
        {
            $startDate = $this->getDayMonthYear($start);
            $endDate = $this->getDayMonthYear($end);

            //get difference date
            $finalDiff =  $this->dateDiffHelper($startDate, $endDate);

        } else if($e > $s){ //if end date is greater than start date
            
            $startDate = $this->getDayMonthYear($end);
            $endDate = $this->getDayMonthYear($start);

            //get difference date
            $finalDiff = $this->dateDiffHelper($startDate, $endDate);
        }


        //get total days and months
        $total = $this->totalMonthAndDay($start, $end);

        $final['year'] = $finalDiff['year'];
        $final['month'] = $finalDiff['month'];
        $final['day'] = $finalDiff['day'];
        $final['total_years'] = $total['total_years'];
        $final['total_months'] = $total['total_months'];
        $final['total_days'] = $total['total_days'];

        return $final;
    }

    //calculate differences in date
    public function dateDiffHelper($startDate, $endDate)
    {
        $startYear = $startDate['year'];
        $startMonth = $startDate['month'];
        $startDay = $startDate['day'];

        $endYear = $endDate['year'];
        $endMonth = $endDate['month'];
        $endDay = $endDate['day'];

        $dayDiff = $startDay - $endDay;
            
        $res = DateSetting::where('year_bs', $startYear)
        ->where('month_bs', $startMonth)->first();

        //if day is less than 1, add the no of days from months and subtract month by 1
        if($dayDiff < 1 )
        {
            $noOfDaysBsMonth = $res->days_bs;
            $newStartDay = $noOfDaysBsMonth + $dayDiff;
            $newStartMonth = $startMonth - 1;
            if($newStartMonth < 1)
            {
                $newStartMonth =12;
                $newStartYear = $startYear - 1;
                $newStartYear = $startYear - $endYear;
            }

            $diffDate = $this->checkMonth($newStartMonth, $endMonth, $startYear, $endYear);
            
        } else { //if day is greater than 1 store day diff as newDay
            $newStartDay = $dayDiff;
            $diffDate = $this->checkMonth($startMonth, $endMonth, $startYear, $endYear); 
        }

        $date['year'] = $diffDate['newStartYear'];
        $date['month'] = $diffDate['newStartMonth'];
        $date['day'] = $newStartDay;

        return $date;
    }

    //if month is less than 1, add 12 and deduct 1 year
    public function checkMonth($startMonth, $endMonth, $startYear, $endYear)
    {
        $monthDiff = $startMonth - $endMonth;

        if($monthDiff < 1)
        {
            $newStartMonth = 12 + $monthDiff;
            $newStartYear = $startYear - 1;
            $newStartYear = $newStartYear - $endYear;

        } else {
            $newStartMonth = $monthDiff;
            $newStartYear = $startYear - $endYear;
        }

        $newDate['newStartYear'] = $newStartYear;
        $newDate['newStartMonth'] = $newStartMonth;

        return $newDate;
    }
    /* public function getAdDate($dayAd, $day, $month, $year, $ad_total_day)
    {  

        $newDay = $dayToAdd + $day;
        if ($newDay > $ad_total_day) {
            $newDay = (int)($newDay % $ad_total_day);
            $month++;
                    
        }

        return $year.'/'.$month.'/'.$newDay;                
    } */


    public function currentNepaliDate()
    {
        return $this->convertBsFromAd($this->currentDate());
    }


    public function currentDate()
    {
        return Carbon::now()->format('Y/m/d');
    }

}