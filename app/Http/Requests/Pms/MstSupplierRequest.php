<?php

namespace App\Http\Requests\Pms;

use Illuminate\Foundation\Http\FormRequest;

class MstSupplierRequest extends FormRequest
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

        $id_check = $this->request->get('id') ? ",".$this->request->get('id') : ",NULL";
        $client_id = $this->request->get('client_id') ? ",".$this->request->get('client_id') : ",NULL";
        $client_check = $id_check.",id,client_id".$client_id.",deleted_uq_code,1";
        return [
            'name_en' => 'required|max:100|unique:mst_suppliers,name_en'.$client_check,
            'name_lc' => 'max:100|unique:mst_suppliers,name_lc'.$client_check,
            // 'country_id'=>'required',
            'email'=>'required|email|unique:mst_suppliers,email'.$client_check,
            'province_id'=>'required',
            'district_id'=>'required',
            'address'=>'required',
            // 'contact_person'=>'required',
            'contact_number'=>'required|numeric|digits:10',
            'description' => 'max:1000',
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
            'code' => 'Code',
            'name_en' => 'Name',
            'name_lc' => 'नाम',
            'email'=>'Email',
            'description' => 'Description',
            'country_id' => 'Country',
            'province_id' => 'Province',
            'district_id' => 'District',
            'address' => 'Address',
            // 'contact_person' => 'Contact Person',
            'contact_number' => 'Contact Number',

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
        ];
    }
}
