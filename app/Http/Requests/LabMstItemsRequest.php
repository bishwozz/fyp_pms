<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LabMstItemsRequest extends FormRequest
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
            'code' => 'required|max:20|unique:lab_mst_items,code'.$code_check,
            'name'=>'required|max:200|unique:lab_mst_items,name'.$name_check,
            'description'=>'max:500',
            'reference_from_value'=>'max:100',
            'reference_from_to'=>'max:100',
            'unit'=>'max:50',
            'price' => 'required',
            'special_reference' => "required_if:is_special_reference,==,1",
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
            'name.unique' => 'Name is already in use.',
            'name.required'=>'Name is required',
            'price.reqiured' => 'Price is required',
        ];
    }
}
