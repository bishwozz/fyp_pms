<?php

// namespace App\Base\Helpers;
namespace App\Utils;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PdfPrint
{
    private static $jsreport_url = "";
    private static $jsreport_host = "";
    private static $jsreport_port = "80";
    public static function printLandscape($content, $file_name, $recipe = "chrome-pdf"){
        return self::print("SJgtdvhyhB", $content, $recipe, "none", $file_name);
    }

    public static function printPortrait($content, $file_name, $recipe = "chrome-pdf"){
        return self::print("HnLh7fslQ", $content, $recipe, "none", $file_name);
    }
    
    private static function loadJsReportConfig(){
        self::$jsreport_url= env('JSREPORT_URL');
        self::$jsreport_port= env('JSREPORT_PORT');
        self::$jsreport_host= env('JSREPORT_HOST');
    }

    public static function print($shortid, $content, $recipe, $engine, $file_name){
        self::loadJsReportConfig();
        try{
	        // $pass = $this->crud->model->find($pass_id);
	        // $rep = $pass->pressRepresentative;
        	// $photo_encoded = "";
	        // $photo_path = public_path('storage/uploads/'.$rep->photo);
	        // // Read image path, convert to base64 encoding
	        // $imageData = base64_encode(file_get_contents($photo_path));
	        //     // Format the image SRC:  data:{mime};base64,{data};
	        // $photo_encoded = 'data: '.mime_content_type($photo_path).';base64,'.$imageData;
	        // //signature
	        // $signature_encoded = "";
        
	        // $signature_path = public_path('storage/uploads/'.$rep->signature);
	        // // Read image path, convert to base64 encoding
	        // $imageData = base64_encode(file_get_contents($signature_path));
	        //     // Format the image SRC:  data:{mime};base64,{data};
	        // $signature_encoded = 'data: '.mime_content_type($signature_path).';base64,'.$imageData;

        	$curl = curl_init();
        } catch (Exception $e) {
        	dd($e);
        }
        try{
	        $tpl_data = array(
	            "template" => [
                    // "shortid" => "SJgtdvhyhB", //landscape
                    "shortid" => $shortid,//portrait
                    'content' => $content,
                    // 'recipe' => 'chrome-pdf',
                    'recipe' => $recipe,
                    'engine' => $engine
                ],
                // "chrome" =>[
                //     "landscape" => true,
                //     "format" => "A4",
                //     "printBackground" => true
                // ],
                "phantom" => [
                    'customPhantomJS' => true,
                    "format" => "A4",
                    // "orientation" => "landscape",
                    // "orientation" => "portrait",
                    "margin" => [
                        "left" => "20px"
                    ]
                    // "margin" => "10px"
                    // "phantomjsVersion" => "2.1.1",
                    // "fitToPage" => true

                ]
	        );
	    }
	    catch(Exception $e){
	    	dd($e);
	    }
    	$data_str = json_encode($tpl_data);
        // dd($data_str);
        // exit();
        // dd()÷÷

        curl_setopt_array($curl, array(
            CURLOPT_PORT => self::$jsreport_port,
            CURLOPT_URL => self::$jsreport_url,
            CURLOPT_RETURNTRANSFER => true, 
            CURLOPT_ENCODING => "",
            CURLOPT_HEADER => 1,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false, 
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            // CURLOPT_POSTFIELDS => "{\n    \"template\": {\n        \"shortid\": \"S1gx8aRnMS\"\n    },\n    \"data\": {\n\t    \n\t}\n}",
            CURLOPT_POSTFIELDS => $data_str,
            CURLOPT_HTTPHEADER => array(
              "Accept: */*",
              "Accept-Encoding: gzip, deflate",
              "Cache-Control: no-cache",
              "Connection: keep-alive",
              "Content-Length: ".strlen($data_str),
              "Content-Type: application/json",
              "Cookie: render-complete=true",
              "Host: ".self::$jsreport_host,
              "cache-control: no-cache"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        // file_put_contents('D:\servers\laragon3.2\www\cmis\public\storage\xyz.pdf',$response);
//         dd($response);
                if ($err) {
            echo "cURL Error #:" . $err;
            dd($err);
        } else {
            header("Content-type:application/pdf");
            // It will be called downloaded.pdf
            header("Content-Disposition:inline;filename=".$file_name);

            echo $response;
        }
        exit();
    }

    /**
     * $path has to be from storage path
     * eg storage_path('uploads/certificates)
     */
    public static function download($content, $path, $file_name, $recipe = "chrome-pdf"){
        // dd($path);
        $response = self::getResponse("SJgtdvhyhB", $content, $recipe, "none", $file_name);
        if ($response) {
        //    $path = storage_path('uploads\certificates');
        //    $name = $file_name.'.pdf';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
                // dd(is_dir($path));
            }
            // $download_path = $path.'\\'.$file_name;
            // $download_path = $path.'\\'.$file_name;
            $download_path = join(DIRECTORY_SEPARATOR, [$path, $file_name]);
            // dd(is_dir($path),$download_path);
            file_put_contents($download_path, $response);
        }
    }


    public static function getResponse($shortid, $content, $recipe, $engine, $file_name){
        self::loadJsReportConfig();
        try{
            $curl = curl_init();
        } catch (Exception $e) {
            dd($e);
        }
        try{
            $tpl_data = array(
                "template" => [
                    // "shortid" => "SJgtdvhyhB", //landscape
                    "shortid" => $shortid,//portrait
                    'content' => $content,
                    // 'recipe' => 'chrome-pdf',
                    'recipe' => $recipe,
                    'engine' => $engine
                ],
                // "chrome" =>[
                //     "landscape" => true,
                //     "format" => "A4",
                //     "printBackground" => true
                // ],
                "phantom" => [
                    'customPhantomJS' => true,
                    "format" => "A4",
                    // "orientation" => "landscape",
                    // "orientation" => "portrait",
                    "margin" => [
                        "left" => "20px"
                    ]
                    // "margin" => "10px"
                    // "phantomjsVersion" => "2.1.1",
                    // "fitToPage" => true

                ]
            );
        }
        catch(Exception $e){
            dd($e);
        }
        // dd($tpl_data);
        $data_str = json_encode($tpl_data);
        // dd($data_str);
        // exit();
        // dd(self::$jsreport_port,self::$jsreport_url);
        curl_setopt_array($curl, array(
            CURLOPT_PORT => self::$jsreport_port,
            CURLOPT_URL => self::$jsreport_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_HEADER => 1,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            // CURLOPT_POSTFIELDS => "{\n    \"template\": {\n        \"shortid\": \"S1gx8aRnMS\"\n    },\n    \"data\": {\n\t    \n\t}\n}",
            CURLOPT_POSTFIELDS => $data_str,
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Content-Length: ".strlen($data_str),
                "Content-Type: application/json",
                "Cookie: render-complete=true",
                "Host: ".self::$jsreport_host,
                "cache-control: no-cache"
            ),
        ));
        // dd($curl);
        // curl_setopt($curl, CURLOPT_POST, 1);
        // curl_setopt($curl, CURLOPT_POSTFIELDS, $data_str);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // dd($curl);
        $response = curl_exec($curl);
        // dd($response);
        if(!$response) {
        DB::rollBack();
          throw new \Exception("प्राविधिक कारणले निवेदन अनुमोदित हुन सकेन !!! पुन प्रयास गर्नुहोस", 1);
        //   \Alert::error('प्राविधिक कारणले निवेदन अनुमोदित हुन सकेन !!! पुन प्रयास गर्नुहोस')->flash();
        //   return redirect()->back();     
        }
        // dd($curl);
        $err = curl_error($curl);
        
        if (empty($err)) {
            return $response;
        }
        return null;
    }
}