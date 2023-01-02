<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Imports\ExcelImport;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

class ExcelUploadController extends Controller
{
    public function index()
    {
        return view('excel.excel_upload');
    }

    public function excelUpload(Request $request)
    {

        
        if (empty($request->file('excel-upload'))) 
        {
            \Alert::error(trans('upload a file'))->flash();
            return redirect()->back();
        }
        else{   
            request()->validate([
                'excel-upload'  => 'required|mimes:xls,xlsx,csv|max:2048',
            ]);

            $filename = $request->file('excel-upload')->getClientOriginalName();
            $check_file = "storage/uploads/excel_upload/".$filename;
            // if($check_file){
            //     \Alert::error(trans('File already uploaded choose '))->flash();
            //     return redirect()->back();
            // }

            $pathTofile = $request->file('excel-upload')->store('uploads/excel_upload','public');
            $pathTofile = "storage/".$pathTofile;
            
            $import =  new ExcelImport;


            try {
                 $import->import($pathTofile); // we are using the trait importable in the xxxImport which allow us to handle it from the controller directly
              } 
            catch (Exception $e) {
                \Alert::error(trans($e->getMessage() .'.<br>   '. ' Check your file format and try again.' ))->flash();
                return redirect()->back();
              }
            if(Session::get('error')){
                \Alert::error(trans(Session::get('error')))->flash();
            }elseif(Session::get('success')){
                \Alert::success(trans(Session::get('success')))->flash();
            }
            else{
                \Alert::error(trans('Something went wrong.'))->flash();
            }
            return redirect()->back();
        
        }
    }

}
