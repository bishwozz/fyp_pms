<?php

namespace App\Http\Requests\HrMaster;

use Illuminate\Foundation\Http\FormRequest;

class HrMstEmployeesRequest extends FormRequest
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
        $is_other_country = $this->request->get('is_other_country');

        if($is_other_country == 0){
            return [
                'full_name' => 'required|max:255',
                'gender_id' => 'required',
                'date_of_birth_bs' => 'required',
                'date_of_birth_ad' => 'required',
                'qualification' => 'required|max:200',
                'department_id' => 'required',
                'sub_department_id' => 'required',
                'province_id' => 'required',
                'district_id' => 'required',
                'local_level_id' => 'required',
                'address' => 'required|max:200',
                'cell_phone' => 'max:10',
                'home_phone' => 'max:10',
                'email' => 'max:100',
                'website' => 'max:200',
                'email' => 'required',
            ];
            
        }
        if($is_other_country == 1){
            return [
                'full_name' => 'required|max:255',
                'gender_id' => 'required',
                'date_of_birth_bs' => 'required',
                'date_of_birth_ad' => 'required',
                'qualification' => 'required|max:200',
                'department_id' => 'required',
                'sub_department_id' => 'required',
                'country_id' => 'required',
                'address' => 'required|max:200',
                'cell_phone' => 'max:10',
                'home_phone' => 'max:100',
                'email' => 'max:100',
                'website' => 'max:200',
                'email' => 'required',
            ];
        }
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
            'full_name.required' => 'Full Name field is empty!!',
            'gender_id.required' => 'Gender field is empty!!',
            'date_of_birth_bs.required' => 'Date of Birth(bs) field is empty!!',
            'date_of_birth_ad.required' => 'Date of birth(ad) field is empty!!',
            'qualification.required' => 'Qualification field is empty!!',
            'department_id.required' => 'Departments field is empty!!',
            'country_id.required' => 'country field is empty!!',
            'province_id.required' => 'provience field is empty!!',
            'district_id.required' => 'District field is empty!!',
            'local_level_id.required' => 'Local Level field is empty!!',
            'address.required' => 'Address field is empty!!',
        ];
    }
}
