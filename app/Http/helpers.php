<?php
use App\Utils\DateHelper;
use App\Utils\NumberToWords;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use App\Base\Helpers\GetNepaliServerDate;

/**
 * Convert BS date to AD
 *
 * @param string|null $date_ad
 * @return string
 */

function ConvertToEnglishWords($number){
    $numberToWords = new NumberToWords();
    return Str::ucfirst(Str::lower($numberToWords->ConvertToEnglishWords($number)));
}

function convert_bs_from_ad(string $date_ad = null) {
    if(empty($date_ad)) {
        $date_ad = Carbon::now()->todateString();
    }

    $dateHelper = new DateHelper();
    return $dateHelper->convertBsFromAd($date_ad);
}

function get_current_fiscal_year(){
    $date_ad = Carbon::now()->todateString();
    $dateHelper = new DateHelper();
    $date_bs =  $dateHelper->convertBsFromAd($date_ad);
    return $dateHelper->fiscalYear($date_bs);
}
/**
 * Convert date from AD to BS
 *
 * @param string|null $date_bs
 * @return string
 */
function convert_ad_from_bs(string $date_bs = null) {
    if(empty($date_bs)) {
        $date_bs = Carbon::now()->todateString();
        return $date_bs;
    }

    $dateHelper = new DateHelper();
    return $dateHelper->convertAdFromBs($date_bs);
}
function gender_image($genderName = 'Male') {
    $image = '/images/user-image.png';
    if($genderName === 'Female') {
        $image = '/images/user-default-female.png';
    }

    return $image;
}
function current_nepali_date_formatted(){
    $nepali_Date = new GetNepaliServerDate();                   
    return $nepali_Date->getNepaliDate();
}


//get all models
function modelCollection()
{
    $res = [];
    $base_path = \base_path().'/app/Models';
    
    $entities = \scandir($base_path);
    Session::forget('temp_output');
    Session::forget('entity_dir');
    Session::forget('all_entry_entities'); 

    fetchAllEntities($base_path,$entities);

    $res['final_output']=Session::get('temp_output');
    $res['entity_dir']= Session::get('entity_dir');
         
    Session::forget('temp_output');
    Session::forget('entity_dir');
    Session::forget('all_entry_entities');
    return $res;
}

//fetch all entities/models in output dir

function fetchAllEntities($base_path,$entities)
{
    foreach($entities as $entity)
    {
        if(\str_starts_with($entity,'.')) continue;  //ignore '.' and '..' files

        if(is_dir($base_path.'/'.$entity)){
            $scan_dir = \scandir($base_path.'/'.$entity);

            $temp_scan_dir = array_map(function($item){
                                if(!str_starts_with($item,'.')) return \strtolower(\substr($item,0,-4));
                            },$scan_dir);
            $new_scan_dir = array_values(array_filter($temp_scan_dir));

            //keep track of master type
            Session::put('entity_dir.'.$entity,$new_scan_dir);

            //if session already hsa entry, merge previous entry with new one and update the session
            if(Session::has('all_entry_entities')){
                $all_entry_entities = Session::get('all_entry_entities');
                $all_entry_entities=array_merge($all_entry_entities,$new_scan_dir);
                Session::put('all_entry_entities',$all_entry_entities);
            }else{
                //else make a new session
                Session::put('all_entry_entities',$new_scan_dir);
            }

            fetchAllEntities($base_path,$scan_dir);
        }else{
            //check if session has entry and push entity to 'Other' only if entry does not exits 
            if(Session::has('all_entry_entities')){
                if(!in_array(\strtolower(\substr($entity,0,-4)),Session::get('all_entry_entities'))){
                    Session::push('entity_dir.Other',\strtolower(\substr($entity,0,-4)));
                }
            }else{
                Session::push('entity_dir.Other',\strtolower(\substr($entity,0,-4)));
            }
            Session::put('temp_output.'.\strtolower(\substr($entity,0,-4)),\substr($entity,0,-4));

        }
    }
}

function dateToString($date)
{
    return Carbon::parse($date)->toDateString();
}

function generate_date_with_extra_days(string $date = null,$days) {
    if(empty($date)) {
        $date = Carbon::now();
        $date->subDays($days);
        return $date;
    }
    $date=Carbon::parse($date);
    $date->subDays($days);

    return Carbon::parse($date)->toDateString();

}

function dateToday(){
    return Carbon::now()->toDateString();
}
function dateTimeNow(){
    return Carbon::now()->toDateTimeString();
}

function lang()
{
    $lang = App::getLocale();

    $lang = $lang == 'np'? 'lc' : $lang;
    return $lang;
}

function current_url()
{
    $url = url()->current();
    return str_replace('/search','',$url);
}

function appSetting(){
    return backpack_user()->clientEntity->appSetting;
}


