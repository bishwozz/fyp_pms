<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppClientRequest extends FormRequest
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
        $id_check = $this->request->get('id') ? ",".$this->request->get('id') : "";
        return[
            'code' => 'max:20',
            'name'=>'required|max:200|unique:app_clients,name'.$id_check,
            'admin_email' => 'required|max:200',
            'remarks' => 'max:1000',
            'prefix_key'=>'required',
            'fed_local_level_id'=>'required',
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
