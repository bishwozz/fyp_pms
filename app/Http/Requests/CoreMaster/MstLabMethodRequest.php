<?php

namespace App\Http\Requests\CoreMaster;

use Illuminate\Foundation\Http\FormRequest;

class MstLabMethodRequest extends FormRequest
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
            'code'=>'required|max:20|unique:mst_lab_samples,code'.$code_check,
            'name'=>'required|max:200|unique:mst_lab_samples,name'.$name_check,
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
