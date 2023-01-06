<?php

namespace App\Http\Requests\Pms;

use Illuminate\Foundation\Http\FormRequest;

class MstCategoryRequest extends FormRequest
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
        $name = $this->request->get('title_en');
        $name_check =$id_check.",id,title_en,".$name.",deleted_uq_code,1";
        $code = $this->request->get('code');
        $code_check = $id_check.",id,code,".$code.",deleted_uq_code,1";
        
        return [
            'code' => 'required|max:20|unique:phr_mst_categories,code'.$code_check,
            'title_en'=>'required|max:200|unique:phr_mst_categories,title_en'.$name_check,
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
            'code' => 'Code',
            'title_en' => 'Title',
            'description' => 'Description',
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
