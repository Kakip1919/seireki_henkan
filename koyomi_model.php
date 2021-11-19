<?php
ini_set('display_errors', "0");

Class Seireki_calculation
{
    protected $seireki;
    protected $reiwa;
    protected $heisei;
    protected $showa;
    protected $month;
    protected $day;
    public $flag;

    public function __construct($seireki,$month,$day)
    {
        $this->seireki = $seireki;
        $this->month = $month;
        $this->day = $day;
        $this->flag = 1;
    }

    public function validation_message(){
        if(empty($this->seireki)){
            $this->flag = 0;
            return "年月日を入力すると和暦に変換されます";
        }
        if($this->month >= 13 && $this->month <= 0){
            $this->flag = 0;
            return "入力された月が範囲外です";
        }
        if(($this->seireki >= 2047) || $this->seireki <= 1925){
            $this->flag = 0;
            return "半角数字で1926年から2047年までの数値で入力してください";
        }
        if(!is_numeric($this->seireki)){
            $this->flag = 0;
            return "半角数字のみの入力を受け付けています。";
        }
    }

    public function seireki_LastTwoDigits()
    {
       return substr($this->seireki, -2);
    }

    public function judge_seireki(){
        $post_seireki = $this->seireki;
        $seireki_two_digits =  $this->seireki_LastTwoDigits();

        //令和
        if($post_seireki <= 2047 && $post_seireki >= 2019){
            $this->reiwa = $seireki_two_digits - 18;
        }
        //平成
        if($post_seireki <= 2018 && $post_seireki >= 1989){
            if($post_seireki >= 2000){
                $this->heisei = $seireki_two_digits + 12;
                return $this->heisei;
            }
            $this->heisei = $seireki_two_digits - 88;
        }
        //昭和
        if($post_seireki <= 1988 && $post_seireki >= 1926){
            $this->showa = $seireki_two_digits - 25;
        }
    }

    public function subtitle(){
        if(!empty($this->seireki) && is_numeric($this->seireki) && $this->seireki >= 1926 && $this->seireki <= 2047){
            if((!empty($this->month) && is_numeric($this->month) && $this->month >= 0 && $this->month <= 13)){
                if(!empty($this->day) && is_numeric($this->day) && $this->day <= 31){
                    return "西暦". $this->seireki."年の".$this->month."月".$this->day."日は、";
                }return "西暦". $this->seireki."年の".$this->month."月は、";
            }return "西暦". $this->seireki."年は、";
        }
    }

    public function result_seireki(){

        $this->judge_seireki();
        if(!empty($this->reiwa)){
            if(!empty($this->month) && is_numeric($this->month) && $this->month >= 0 && $this->month <= 13){
                if(!empty($this->day) && is_numeric($this->day) && $this->day <= 31){
                     return "令和".$this->reiwa."年の".$this->month."月".$this->day."日です";
                } return "令和".$this->reiwa."年の".$this->month."月です";
            } return "令和".$this->reiwa."年です";
        }

        if(!empty($this->heisei)) {
            if(!empty($this->month) && is_numeric($this->month) && $this->month >= 0 && $this->month <= 13){
                if(!empty($this->day) && is_numeric($this->day) && $this->day <= 31){
                    return "令和".$this->heisei."年の".$this->month."月".$this->day."日です";
                }
                return "令和".$this->heisei."年の".$this->month."月です";
            }
            return "平成" . $this->heisei."年です";
        }

        if(!empty($this->showa)){
            if(!empty($this->month) && is_numeric($this->month) && $this->month >= 0 && $this->month <= 13){
                if(!empty($this->day) && is_numeric($this->day) && $this->day <= 31){
                    return "令和".$this->showa."年の".$this->month."月".$this->day."日です";
                }
                return "令和".$this->showa."年の".$this->month."月です";
            }
            return "昭和".$this->showa."年です";
        }
    }
}


//TabBの和暦　⇒　西暦変換　を行うためのクラス
Class Wareki_calculation
{
    protected $wareki;
    protected $example;
    protected $month;
    protected $day;
    public $flag;
    public function __construct($wareki,$example,$month,$day)
    {
        $this->wareki = $wareki;
        $this->example = $example;
        $this->month = $month;
        $this->day = $day;
        $this->flag = 1;
    }
    //和暦　バリデーションチェック　これでは弱いので別のバリデーションが必要
    public function wareki_validation_message()
    {
        if (empty($this->wareki) && empty($this->seireki)) {
            $this->flag = 0;
            return "和暦を入力すると西暦に変換されます。";
        }
        if(($this->wareki >= 31 && $this->example === "reiwa") || $this->example === "heisei"){
            $this->flag = 0;
            return "令和、平成は31年以上の入力ができません";
        }
        if(!empty($this->month) && $this->month >= 12){
            $this->flag = 0;
            return "存在しない月が入力されています";
        }
        if (!empty($this->day) && $this->day >= 31) {
            $this->flag = 0;
            return "存在しない日が入力されています";
        }
        if(!is_numeric($this->wareki)){
            $this->flag = 0;
            return "半角数字を入力してください。";
        }
    }

    public function reiwa_rogic()
    {
        if($this->example === "reiwa" && $this->wareki >= 1 && $this->wareki <= 31 && $this->flag === 1){
            if(!empty($this->month) && is_numeric($this->month) && $this->month >= 0 && $this->month <= 13){
                if(!empty($this->day) && is_numeric($this->day) && $this->day >= 0 && $this->day <= 31){
                    return $this->wareki + 2000 + 18 ."年".$this->month."月".$this->day."日です";
                }return $this->wareki + 2000 + 18 ."年".$this->month."月です";
            }return $this->wareki + 2000 + 18 ."年です";
        }
    }

    public function heisei_rogic()
    {
        if ($this->example === "heisei" && $this->wareki >= 1 && $this->wareki <= 31 && $this->flag === 1) {
            if (!empty($this->month) && is_numeric($this->month) && $this->month >= 0 && $this->month <= 13) {
                if (!empty($this->day) && is_numeric($this->day) && $this->day >= 0 && $this->day <= 31) {
                    return "西暦" . ($this->wareki + 2000 - 12) . "年" . $this->month . "月" . $this->day . "日です";
                }return "西暦" . ($this->wareki + 2000 - 12) . "年" . $this->month . "月です";
            }return "西暦" . ($this->wareki + 2000 - 12) . "年です";
        }
        if ($this->example === "heisei" && $this->wareki == 31 && $this->flag === 1) {
            if (!empty($this->month) && is_numeric($this->month) && $this->month >= 0 && $this->month <= 13) {
                if (!empty($this->day) && is_numeric($this->day) && $this->day >= 0 && $this->day <= 31) {
                    return "西暦" . ($this->wareki + 2000 - 12) . "年" . $this->month . "月" . $this->day . "日です";
                }return "西暦" . ($this->wareki + 2000 - 12) . "年" . $this->month . "月です";
            }return "西暦" . ($this->wareki + 2000 - 12) . "年です";
        }
    }

    public function showa_rogic()
    {
        if ($this->example === "showa" && $this->wareki >= 1 && $this->wareki <= 63 && $this->flag === 1){
            if (!empty($this->month) && is_numeric($this->month) && $this->month >= 0 && $this->month <= 13) {
                if (!empty($this->day) && is_numeric($this->day) && $this->day <= 31) {
                    return $this->wareki + 1900 + 25 . "年" . $this->month . "月" . $this->day . "日です";
                }return $this->wareki + 1900 + 25 . "年" . $this->month . "月です";
            }return $this->wareki + 1900 + 25 . "年です";
        }
        if($this->example === "showa" && $this->wareki === 64 && $this->flag === 1){
            if (!empty($this->month) && is_numeric($this->month) && $this->month >= 0 && $this->month <= 13) {
                if (!empty($this->day) && is_numeric($this->day) && $this->day <= 31) {
                    return "西暦" . ($this->wareki + 1900 + 25) . "年" . $this->month . "月" . $this->day . "日です";
                }return "西暦" . ($this->wareki + 1900 + 25) . "年" . $this->month . "月です";
            }return "西暦" . ($this->wareki + 1900 + 25) . "年です";
        }
    }

    public function switch_subtitle(){
        switch ($this->example) {
            case"reiwa":
                return "令和";
            case"heisei":
                return "平成";
            case"showa":
                return "昭和";

        }
    }

    public function wareki_subtitle()
    {
        if(!empty($this->wareki) && is_numeric($this->wareki) && $this->wareki >= 1 && $this->wareki <= 31){
            if((!empty($this->month) && is_numeric($this->month) && $this->month >= 0 && $this->month <= 13)){
                if(!empty($this->day) && is_numeric($this->day) && $this->day <= 31){
                    return $this->switch_subtitle().$this->wareki."年".$this->month."月".$this->day."日は、";
                }return $this->switch_subtitle().$this->wareki."年".$this->month."月は、";
            }return $this->switch_subtitle().$this->wareki."年は、";
        }
    }

    public function two_dot_return()
    {
        switch($this->example){
            case "reiwa":
                return $this->reiwa_rogic();
            case "heisei":
                return $this->heisei_rogic();
            case "showa":
                return $this->showa_rogic();
        }
    }

//
//            ($this->example === "reiwa" && $this->wareki >= 1 && $this->wareki <= 31):
//
//
//            return $this->wareki + 2000 + 18 ."年".$this->month."月".$this->day."日です";
//
//            return $this->wareki + 2000 + 18 ."年です";
//
//
//            ($this->example === "heisei" && $this->wareki >= 1 && $this->wareki <= 30):
//
//                return $this->wareki + 2000 - 12 ."年".$this->month."月".$this->day."日です";
//
//                return $this->wareki + 2000 - 12 ."年".$this->month."月です";
//
//                return $this->wareki + 2000 - 12 ."年です";
//
//
//                if ($this->example === "showa" && $this->wareki >= 1 && $this->wareki <= 63) {
//                        return $this->wareki + 1900 + 25 . "年" . $this->month . "月" . $this->day . "日です";
//
//                    return $this->wareki + 1900 + 25 . "年" . $this->month . "月です";
//
//                return $this->wareki + 1900 + 25 . "年です";
//

}