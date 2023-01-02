<?php

namespace App\Imports;

use App\Models\Lab\LabMstItems;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Lab\LabPatientTestData;
use App\Models\Lab\LabPatientTestResult;
use App\Models\CoreMaster\MstFedDistrict;
use App\Models\CoreMaster\MstIndustryType;
use App\Models\CoreMaster\MstFedLocalLevel;
use App\Models\CoreMaster\MstOwnershiptype;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;


class ExcelImport implements ToCollection, WithHeadingRow,  SkipsOnError, SkipsOnFailure 
{
    /**
    * @param Collection $collection
    */
    use Importable, SkipsErrors, SkipsFailures;
    
 
    public function collection(Collection $rows)
    {
        $rows = $rows->skip(9);
        $prev_value = '';
        if(isset($rows[9][5])){
            if($rows[9][5] != 'Dye'){
                return back()->with('error', "The Excel format doesn't match.");
            }
        }else{
            return back()->with('error', "The Excel format doesn't match.");
        }

        DB::beginTransaction();
        try{
            foreach ($rows as $key => $row){

                foreach ($row as $key1 => $col){
                    $index = array_search($key1, array_keys($row->toArray()));
                    if ($index  ==  1) {
                        $sample_id =  $key1;
                    }
                }

                $LabPatientTestResult = LabPatientTestResult::where('barcode',$row[$sample_id])->get();
                // dd($LabPatientTestResult,$row[$sample_id]);
                if($LabPatientTestResult){

                    $sample_barcode_id =  $row[$sample_id];
                    $sample_dye = isset($row[5])?$row[5]:0;
                    $sample_ct =  isset($row[9])?$row[9]:0;
                    $sample_result =  isset($row[17])?$row[17]:0;
                    $item_name = null;
                    
                    if($sample_dye == 'FAM'){
                        $item_name = 'E- gene';
                    }
                    if($sample_dye == 'VIC'){
                        $item_name = 'RdRp  gene';
                    }
                    if($sample_dye == 'ROX'){
                        $item_name = 'N gene';
                    }
                    if($sample_dye == 'ROX (Texas Red)'){
                        $item_name = 'N gene';
                    }
                    if($sample_dye == 'CY5'){
                        $item_name = 'Final Result';
                    }
                    if($item_name){
                        // $lab_item = LabMstItems::where('name','=',trim($item_name))->first();
                        $lab_item = LabMstItems::where('name','iLike','%'.trim($item_name).'%')->first();

                        if($lab_item){
                            foreach($LabPatientTestResult as $result){

                                if($result->barcode == $sample_barcode_id ){
                                    if(!$sample_result){
                                        $sample_result = $prev_value;
                                    }else{
                                        $prev_value = $sample_result;
                                    }
                                }
    
                                if($lab_item->id == $result->lab_item_id){
                                    if($result->lab_item_id == 5){
                                        $result->result_value =  $sample_result;
                                    }else{
                                        $result->result_value =  $sample_ct;
                                    }
                                    $result->save();
                                }
                            }
                        }

                    }

                }
            }
            
            DB::commit();
            return back()->with('success', "Data successfully imported.");
        }catch(Exception $e){
            DB::rollback();
            return $e->getMessage();
        }
    }
}