<?php
namespace Database\Seeders;

use App\DateSetting;
use Illuminate\Database\Seeder;

class DateSettingSeeder extends Seeder
{
    public function run()
    {
        $this->seedData();
    }

    public function seedData()
    {
        $filename = __DIR__.'/date_setting.csv';
        // dd($filename);
        $the_big_array = [];
        // Open the file for reading
        if (($h = fopen("{$filename}", "r")) !== false) {
            // Each line in the file is converted into an individual array that we call $data
            // The items of the array are comma separated
            while (($data = fgetcsv($h, 1000, ",")) !== false) {
                // Each individual array is being pushed into the nested array
                $the_big_array[] = $data;
            }
            // Close the file
            fclose($h);
        }
        // Display the code in a readable format
        $date_data = [];
        foreach ($the_big_array as $key => $value) {
            $eng_date_time = explode(' ', $value[1]);
            $res['eng_date'] = $eng_date_time[0];
            $res['nepali_date'] = $value[2];
            $res['days_bs'] = $value[3];
            $bs_year_month = explode('/', $value[2]);
            $ad_year_month = explode('/', $eng_date_time[0]);
            $numberOfDaysAd = cal_days_in_month(CAL_GREGORIAN, $ad_year_month[1], $ad_year_month[2]);
            $eng_date_ad = $ad_year_month[2] .'/'.$ad_year_month[1].'/'.$ad_year_month[0];
            if ($eng_date_ad ?? 'ok');
            DateSetting::create([
                'date_ad' => $eng_date_ad,
                'date_bs' => $res['nepali_date'],
                'days_bs' => $res['days_bs'],
                'year_bs' => $bs_year_month[0],
                'month_bs' => $bs_year_month[1],
                'days_ad' => $numberOfDaysAd,
                'year_ad' => $ad_year_month[2],
                'month_ad' => $ad_year_month[1],
            ]);
            $date_data[] = $res;
        }
    }
}
