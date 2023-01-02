<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LabMstCategoriesRequest extends FormRequest
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
        $title = $this->request->get('title');
        $title_check =$id_check.",id,title,".$title.",deleted_uq_code,1";
        $code = $this->request->get('code');
        $code_check = $id_check.",id,code,".$code.",deleted_uq_code,1";

        return [
            'code' => 'required|max:20|unique:lab_mst_categories,code'.$code_check,
            'title'=>'required|max:200|unique:lab_mst_categories,title'.$title_check,
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
            'title.required'=>'Name is required.',
        ];
    }
}
