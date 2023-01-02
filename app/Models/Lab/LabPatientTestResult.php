<?php

namespace App\Models\Lab;

use App\Models\Lab\LabGroup;
use App\Models\Lab\LabPanel;
use App\Models\Lab\LabMstItems;
use App\Models\Lab\LabPatientTestData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabPatientTestResult extends Model
{
    use HasFactory;
    protected $table = 'lab_patient_test_results';

    public static $flag_options = [
        0 => '-',
        1 => 'High',
        2 => 'Normal',
        3 => 'Low'
    ];
    public static $flag_options_short = [
        0 => '-',
        1 => 'H',
        2 => 'N',
        3 => 'L'
    ];

    public function labPatientTestData()
    {
        return $this->belongsTo(LabPatientTestData::class,'patient_test_data_id','id');
    }
    public function panel()
    {
        return $this->belongsTo(LabPanel::class,'lab_panel_id','id');
    }
    public function group()
    {
        return $this->belongsTo(LabGroup::class,'lab_group_id','id');
    }
    public function item()
    {
        return $this->belongsTo(LabMstItems::class,'lab_item_id','id');
    }
}