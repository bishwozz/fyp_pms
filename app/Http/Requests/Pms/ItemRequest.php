<?php

namespace App\Http\Requests\Pms;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {


        $id_check = request()->request->get('id') ? ",".request()->request->get('id') : ",NULL";
        $name = $this->request->get('name');
        $name_check =$id_check.",id,name,".$name.",deleted_uq_code,1";
        $code = $this->request->get('code');
        $code_check = $id_check.",id,code,".$code.",deleted_uq_code,1";
        
        return [
            'code' => 'required|max:20|unique:phr_items,code'.$code_check,
            'name'=>'required|max:200|unique:phr_items,name'.$name_check,
            'brand_id'=>'required',
            'category_id'=>'required',
            'supplier_id'=>'required',
            'unit_id'=>'required',
            'stock_alert_minimun'=>'required',
            'category_id'=>'required',
            'description'=>'max:500',
        ];

    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'code' => 'code',
            'name' => 'Name',
            'description' => 'Description',
            'category_id' => 'Category',
            'supplier_id' => 'Supplier',
            'brand_id' => 'Brand',
            'unit_id' => 'Unit',
            'stock_alert_minimun' => 'Stock Alert Minimum',
            'tax_vat' => 'tax_vat',
            'is_taxable' => 'taxable field',
            'supplier_id' => 'Suppiler',

        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'The :attribute field is required.',
            'unique' => 'The :attribute has already been taken.',
            'max' => 'The :attribute must not be greater than :max.',
            'tax_vat.required_if' => 'Tax Vat field is required when Taxable Field is selected Yes'

        ];
    }
}
