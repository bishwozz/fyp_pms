<?php

namespace App\Http\Requests\CoreMaster;

use Illuminate\Foundation\Http\FormRequest;

class AppSettingRequest extends FormRequest
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
        return [
            'office_name'=>'max:200|unique:app_settings,office_name'.$id_check,
            'remarks' => 'max:500',
            'fiscal_year_id' =>'required',
            'address_name' => 'required',
            'patient_seq_key' => 'required',
            'bill_seq_key' => 'required',
            'order_seq_key' => 'required',
            'sample_seq_key' => 'required',
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
            //
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
            //
        ];
    }
}
