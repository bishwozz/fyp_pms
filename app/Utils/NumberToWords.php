<?php

namespace App\Utils;

class NumberToWords
{ 
    public function ConvertToEnglishWords(float $num){
        return $this->ConvertToEnglishWord($num);
    }


     public static function ConvertToEnglishWord(float $number){

        if( $number == 0 || $number < 0)
            return '-';

        
        $no = floor($number);
        $rs = $no;
        $point = intval(round(($number - $no) * 100, 0)) ;
        $paisa = $point;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            0 =>" ",
            1 => "ONE",
            2 => "TWO",
            3 => "THREE",
            4 => "FOUR",
            5 => "FIVE",
            6 => "SIX",
            7 => "SEVEN",
            8 => "EIGHT",
            9 => "NINE",
            10 => "TEN",
            11 => "ELEVEN",
            12 => "TWELVE",
            13 => "THIRTEEN",
            14 => "FOURTEEN",
            15 => "FIFTEEN",
            16 => "SIXTEEN",
            17 => "SEVENTEEN",
            18 => "EIGHTEEN",
            19 => "NINETEEN",
            20 => "TWENTY",
            21 => "TWENTY ONE",
            22 => "TWENTY TWO",
            23 => "TWENTY THREE",
            24 => "TWENTY FOUR",
            25 => "TWENTY FIVE ",
            26 => "TWENTY SIX",
            27 => "TWENTY SEVEN",
            28 => "TWENTY EIGHT",
            29 => "TWENTY NINE",
            30 => "THIRTY",
            31 => "THIRTY ONE",
            32 => "THIRTY TWO",
            33 => "THIRTY THREE",
            34 => "THIRTY FOUR",
            35 => "THIRTY FIVE",
            36 => "THIRTY SIX",
            37 => "THIRTY SEVEN",
            38 => "THIRTY EIGHT",
            39 => "THIRTY NINE",
            40 => "FORTY", 
            41 => "FORTY ONE",
            42 => "FORTY TWO",
            43 => "FORTY THREE",
            44 => "FORTY FOUR",
            45 => "FORTY FIVE",
            46 => "FORTY SIX",
            47 => "FORTY SEVEN",
            48 => "FORTY EIGHT",
            49 => "FORTY NINE",
            50 => "FIFTY", 
            51 => "FIFTY ONE",
            52 => "FIFTY TWO",
            53 => "FIFTY THREE",
            54 => "FIFTY FOUR",
            55 => "FIFTY FIVE",
            56 => "FIFTY SIX",
            57 => "FIFTY SEVEN",
            58 => "FIFTY EIHT",
            59 => "FIFTY NINE",
            60 => "SIXTY", 
            61 => "SIXTY ONE", 
            62 => "SIXTY TWO", 
            63 => "SIXTY THREE", 
            64 => "SIXTY FOUR",
            65 => "SIXTY FIVE",
            66 => "SIXTY SIX",
            67 => "SIXTY SEVEN",
            68 => "SIXTY EIGHT",
            69 => "SIXTY NINE",
            70 => "SEVENTY", 
            71 => "SEVENTY ONE",
            72 => "SEVENTY TWO",
            73 => "SEVENTY THREE",
            74 => "SEVENTY FOUR",
            75 => "SEVENTY FIVE",
            76 => "SEVENTY SIX",
            77 => "SEVENTY SEVEN",
            78 => "SEVENTY EIGHT",
            79 => "SEVENTY NINE",
            80 => "EIGHTY", 
            81 => "EIGHTY ONE",
            82 => "EIGHTY TWO",
            83 => "EIGHTY THREE",
            84 => "EIGHTY FOUR",
            85 => "EIGHTY FIVE",
            86 => "EIGHTY SIX",
            87 => "EIGHTY SEVEN",
            88 => "EIGHTY EIGHT",
            89 => "EIGHTY NINE",
            90 => "NINETY",
            91 => "NINETY ONE",
            92 => "NINETY TWO",
            93 => "NINETY THREE",
            94 => "NINETY FOUR",
            95 => "NINETY FIVE",
            96 => "NINETY SIX",
            97 => "NINETY SEVEN",
            98 => "NINETY EIGHT",
            99 => "NINETY NINE",
            100 => "HUNDRED");

            $digits = array('', 'HUNDRED','THOUSAND','LAKH', 'CRORE');
   
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? '' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' ' : null;
                $str [] = ($number < 100) ? $words[$number] . " " . $digits[$counter] . $plural . " " . $hundred : $words[floor($number / 10) * 10] . " " . $words[$number % 10] . " " . $digits[$counter] . $plural . " " . $hundred;
            } else $str[] = null;
        
        }

   
            if($rs > 0 && $paisa >= 1){
            
                $str = array_reverse($str);
                $result = implode('', $str);
                $points = ($point) ? " " . $words[$point] . " "  : '';
                return $result . "RUPEES AND" . $points . " PAISA ONLY.";

            }elseif( $rs > 0 && $paisa < 1 ){

                $str = array_reverse($str);
                $result = implode('', $str);
                return $result . "RUPEES ONLY.";

            }elseif( $rs == 0 && $paisa == 0 ){

                return  "ZERO RUPEES AND ZERO PAISA ONLY.";

            }else{

                $str = array_reverse($str);
                $points = ($point) ? " " . $words[$point] . " "  : '';
                return  $points . " PAISA ONLY.";

            }

        }

    public function ConvertToNepaliWords(float $num){
        return $this->ConvertToNepaliWord($num);
    }
    public static function ConvertToNepaliWord(float $num){
        if( $num == 0 || $num < 0)
            return '-';
        function convertToStandardNumber($num) {
    
            $standard_numsets = array("0","1","2","3","4","5","6","7","8","9","-","/");
            $devanagari_numsets = array("०","१","२","३","४","५","६","७","८","९","-","/");
            
            return str_replace($devanagari_numsets, $standard_numsets, $num);
          }
        $number = convertToStandardNumber($num);

        $no = floor( $number );

        $rs = $no;  
        $point = intval(round(($number - $no) * 100, 0)) ;
        $paisa = $point;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array( 
           
            0 => "शुन्य",
            1 => "एक",
            2 => "दुई",
            3 => "तीन",
            4 => "चार",
            5 => "पाँच",
            6 => "छ",
            7 => "सात",
            8 => "आठ",
            9 => "नौ",
            10 =>"दश",
            11 =>"एघार",
            12 =>"बाह्र",
            13 =>"तेह्र",
            14 =>"चौध",
            15 =>"पन्ध्र",
            16 =>"सोह्र",
            17 =>"सत्र",
            18 =>"अठार",
            19 =>"उन्नाइस",
            20 =>"विस",
            21 =>"एक्काइस",
            22 =>"बाइस",
            23 =>"तेईस",
            24 =>"चौविस",
            25 =>"पच्चिस",
            26 =>"छब्बिस",
            27 =>"सत्ताइस",
            28 =>"अठ्ठाईस",
            29 =>"उनन्तिस",
            30 =>"तिस",
            31 =>"एकत्तिस",
            32 =>"बत्तिस",
            33 =>"तेत्तिस",
            34 =>"चौँतिस",
            35 =>"पैँतिस",
            36 =>"छत्तिस",
            37 =>"सैँतीस",
            38 =>"अठतीस",
            39 =>"उनन्चालीस",
            40 =>"चालीस",
            41 =>"एकचालीस",
            42 =>"बयालीस",
            43 =>"त्रियालीस",
            44 =>"चवालीस",
            45 =>"पैँतालीस",
            46 =>" छयालीस",
            47 =>"सच्चालीस",
            48 =>"अठचालीस",
            49 =>"उनन्चास",
            50 =>"पचास",
            51 =>"एकाउन्न",
            52 =>"बाउन्न",
            53 =>"त्रिपन्न",
            54 =>"चउन्न",
            55 =>"पचपन्न",
            56 =>"छपन्न",
            57 =>"सन्ताउन्न",
            58 =>"अन्ठाउन्न",
            59 =>"उनन्साठी",
            60 =>"साठी",
            61 =>"एकसट्ठी",
            62 =>"बयसट्ठी",
            63 =>"त्रिसट्ठी",
            64 =>"चौंसट्ठी",
            65 =>"पैंसट्ठी",
            66 =>"छयसट्ठी",
            67 =>"सतसट्ठी",
            68 =>"अठसट्ठी",
            69 =>"उनन्सत्तरी",
            70 =>"सत्तरी",
            71 =>"एकहत्तर",
            72 =>"बहत्तर",
            73 =>"त्रिहत्तर",
            74 =>"चौहत्तर",
            75 =>"पचहत्तर",
            76 =>"छयहत्तर",
            77 =>"सतहत्तर",
            78 =>"अठहत्तर",
            79 =>"उनासी",
            80 =>"असी",
            81 =>"एकासी",
            82 =>"बयासी",
            83 =>"त्रियासी",
            84 =>"चौरासी",
            85 =>"पचासी",
            86 =>"छयासी",
            87 =>"सतासी",
            88 =>"अठासी",
            89 =>"उनान्नब्बे",
            90 =>"नब्बे",
            91 =>"एकान्नब्बे",
            92 =>"बयानब्बे",
            93 =>" त्रियान्नब्बे",
            94 =>"चौरान्नब्बे",
            95 =>"पन्चानब्बे",
            96 =>"छयान्नब्बे",
            97 =>"सन्तान्नब्बे",
            98 =>"अन्ठान्नब्बे",
            99 =>"उनान्सय",
            100 =>"एक सय",
        );
    
        $digits = array('', 'सय', 'हजार', 'लाख', 'करोड');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? '' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' ' : null;
                $str [] = ($number < 100) ? $words[$number] . " " . $digits[$counter] . $plural . " " . $hundred : $words[floor($number / 10) * 10] . " " . $words[$number % 10] . " " . $digits[$counter] . $plural . " " . $hundred;
            } else $str[] = null;
        }
       
        if($rs > 0 && $paisa >= 1){
            
            $str = array_reverse($str);
            $result = implode('', $str);
            $points = ($point) ? " " . $words[$point] . " "  : '';
            return $result . "रुपैया" . $points . " पैसा मात्र";

        }elseif( $rs > 0 && $paisa < 1 ){

            $str = array_reverse($str);
            $result = implode('', $str);
            return $result . "रुपैया मात्र";

        }elseif( $rs == 0 && $paisa == 0 ){

            return  "शुन्य रुपैया शुन्य पैसा मात्र";

        }else{

            $str = array_reverse($str);
            $points = ($point) ? " " . $words[$point] . " "  : '';
            return  $points . " पैसा मात्र";

        } 
    }
    

};