<?php


namespace App\Utils;


class Utils
{
    //TODO: terminar DNI y NIF
    public static function checkNIF($value){
        $res = ['msg'=>null,'status'=>false];
        $DNI = ['T','R','W','A','G','M','Y','F','P','D','X','B','N','J','Z','S','Q','V','H','L','C','K','E'];
        if(!(strlen($value)>1)){
            $res['msg'] = 'El valor debe contener al menos 1 número y una letra.';
            return $res;
        }
        if(strlen($value) > 9){
            $res['msg'] = 'El valor excede el número de caracteres.';
            return $res;
        }
        //dd(in_array(strtoupper(substr($value,-1)),$DNI));
        if(!in_array(strtoupper(substr($value,-1)),$DNI)){
            $res['msg'] = 'El caracter de comprobación no es correcto';
            return $res;
        }
        return ['msg'=>'', 'status'=>true];
    }
    public static function checkEmail($value){
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
    public static function checkNames($value){
        return preg_match("/^[a-zA-Z-' ]*$/",$value);
    }
    public static function checkPhones($value){
        return preg_match("/^[0-9-' ]*$/",$value);
    }
    public static function checkLen($value, $len){
        return (strlen($value) >= $len);
    }
}
