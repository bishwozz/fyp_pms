<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HrMaster\HrMstSubDepartments;

class DrpartmentSubDepartmentController extends Controller
{
    public function index(Request $request, $value)
    {
        $search_term = $request->input('q');
        $form = collect($request->input('form'))->pluck('value', 'name');

        $options = HrMstSubDepartments::query();
        // if no district has been selected, show no options in localevel
        if (!data_get($form, $value)) {
            return [];
        }

        // if a district has been selected, only show localevel from that district
        if (data_get($form, $value)) {
            $options = $options->where('department_id', $form[$value]);
        }

        if ($search_term) {
            $results = $options->where('title', 'LIKE', '%' . $search_term . '%')->paginate(10);
        } else {
            $results = $options->paginate(10);
        }

        return $options->paginate(10);
    }
}
