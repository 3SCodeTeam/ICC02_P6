<?php


namespace App\Utils;


class MiscTools
{
    public static function safeUserData($user_data){
        $data=[];
        foreach ($user_data as $k => $v){
            if(!in_array($k, ['pass', 'password'])){
                $data[$k] = $v;
            }
        }
        return $data;
    }

}
