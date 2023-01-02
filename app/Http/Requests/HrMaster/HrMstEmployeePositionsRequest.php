<?php

namespace App\Http\Requests\HrMaster;

use Illuminate\Foundation\Http\FormRequest;

class HrMstEmployeePositionsRequest extends FormRequest
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
        return [
            'code'=>'max:20',
            'title_lc'=>'required|max:200',
            'title_en'=>'required|max:200',
            'description_en' => 'max:500',
            'description_lc' => 'max:500',
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
            'title_lc.required'=>'नाम आवश्यक छ',
            'title_en.required'=>'Name आवश्यक छ',
        ];
    }
}
