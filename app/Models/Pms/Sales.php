<?php

namespace App\Models\Pms;

use App\Base\BaseModel;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Sales extends BaseModel
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'sales';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [
    //     'full_name', 'gender_id', 'age', 'contact_number',
    //     'address', 'date_ad', 'date_bs', 'bill_no'
    // ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getCustomerName()
    {
        $corporateCustomer = $this->customerEntity->is_coorporate;
        if($corporateCustomer){
            return $this->customerEntity->company_name.'</br><hr>'.$this->customerEntity->name_en;
        }else{
            return $this->customerEntity->name_en;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function storeEntity()
    {
        return $this->belongsTo(MstStore::class, 'store_id', 'id');
    }
    public function gender()
    {
        return $this->belongsTo('App\Models\MstGender', 'gender_id', 'id');
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItems::class, 'sales_id', 'id');
    }

    public function saleReturnItems()
    {
        if (($this->status_id === 4) || ($this->status_id === 5)) {
            return SaleItems::where('sales_id', $this->id)->where('return_qty', '>', 0)->get();
        }
    }

    public function supStatus()
    {
        return $this->belongsTo(SupStatus::class, 'status_id', 'id');
    }


    public function customerEntity()
    {
        return $this->belongsTo(MstCustomer::class, 'customer_id', 'id');
    }

    // public function getBill()
    // {
    //     // $route = request()->path();
    //     // $route = substr($route, 0, -7);

    //     if (($this->status_id == SupStatus::PARTIAL_RETURN) || $this->status_id == SupStatus::FULL_RETURN) {
    //         // $parameter = '<b><a href="#" title="Click to Edit">' . $this->return_bill_no . '</a></b>';
    //         $parameter = '<b>' . $this->bill_no . '</b>';
    //     } else {
    //         // $parameter = '<b><a href="' . url($route . '/' . $this->id . '/edit') . '" title="Click to Edit">' . $this->bill_no . '</a></b>';
    //         $parameter = '<b><a href=' . '"sales/' . $this->id . '/edit" title="Click to Edit">' . $this->bill_no . '</a></b>';
    //     }
    //     return $parameter;
    // }

    public function getBill()
    {
        $billNum = $this->bill_no;
        if($billNum){
            $sequence = MstSequence::find($billNum)->sequence_code;
            if (($this->status_id == SupStatus::APPROVED)) {
                return '<b>' . $sequence . '</b>';
            } else{
                return '<b style="text-align: center;"> - </b>';
            }
        }else{
            return '-';
        }
        // if (($this->status_id == SupStatus::APPROVED) || ($this->status_id == SupStatus::PARTIAL_RETURN) || ($this->status_id == SupStatus::FULL_RETURN)) {
        //     return '<b><a href=' . '"sales/' . $this->id . '/edit" title="Click to Edit">' . $this->bill_no . '</a></b>';
        // }
    }

    public function getReturnNumber()
    {
        $retSeNum = $this->return_bill_no;
        if($retSeNum){
            $sequence = MstSequence::find($retSeNum)->sequence_code;
            if (($this->status_id == SupStatus::PARTIAL_RETURN) || ($this->status_id == SupStatus::FULL_RETURN)) {
                return '<b>' . $sequence . '</b>';
            } else{
                return '<b style="text-align: center;"> - </b>';
            }
        }else{
            return '-';
        }

    }



    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function printInvoice()
    {
        if ($this->status_id == SupStatus::APPROVED) {
            return '<a href=' . '"sales/' . $this->id . '/Invoice" target=' . '"_blank' . '" class=' . '"btn btn-sm btn-link btn-primary show-btn' . '" title=' . '"Print the Invoice Receipt with Header' . '"><i class=' . '"fa fa-print' . '" aria-hidden=' . '"true' . '"></i></a>';
        }
    }

    public function printReturnsInvoice()
    {
        if ($this->status_id == 4 || $this->status_id == 5) {
            return '<a href=' . '"sales/' . $this->id . '/ReturnInvoice" target=' . '"_blank' . '" class=' . '"btn btn-sm btn-danger' . '" title=' . '"Print the Return Invoice Receipt with Header' . '"><i class=' . '"fa fa-print' . '" aria-hidden=' . '"true' . '"></i></a>';
        }
    }

    public function printInvoiceNoHeader()
    {
        if ($this->status_id == SupStatus::APPROVED) {
            return '<a href=' . '"sales/' . $this->id . '/InvoiceNoHeader" target=' . '"_blank' . '" class=' . '"btn btn-sm btn-link btn-primary show-btn' . '" title=' . '"Print the Invoice Receipt Without Header' . '"><i class=' . '"fa fa-file-text' . '" aria-hidden=' . '"true' . '"></i></a>';
        }
    }

    public function printReturnsInvoiceNoHeader()
    {
        if ($this->status_id == 4 || $this->status_id == 5) {
            return '<a href=' . '"sales/' . $this->id . '/ReturnInvoiceNoHeader" target=' . '"_blank' . '" class=' . '"btn btn-sm btn-danger' . '" title=' . '"Print the Return Invoice Receipt Without Header' . '"><i class=' . '"fa fa-file-text' . '" aria-hidden=' . '"true' . '"></i></a>';
        }
    }

    public function salesReturn()
    {
        if (($this->status_id === SupStatus::APPROVED)) {
            if ((($this->is_return === false) )) {
                return '<a href=' . '"sales-return/' . $this->id . '" btn btn-sm btn-danger' . '" title=' . '"Return the Sales' . '"><i class=' . '"fa fa-repeat' . '" aria-hidden=' . '"true' . '"></i></a>';
            }
        }
    }

    public function showReturnButton()
    {
        if (($this->status_id == SupStatus::PARTIAL_RETURN) || ($this->status_id == SupStatus::FULL_RETURN)) {
            return '<a href=' . '"sales/' . $this->id . '/showReturn" target=' . '"_blank' . '" class=' . '"btn btn-sm btn-danger' . '" title=' . '"Show return invoice' . '"><i class=' . '"fa fa-eye' . '" aria-hidden=' . '"true' . '"></i></a>';
        }
    }
}
