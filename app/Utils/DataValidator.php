<?php


namespace App\Utils;



use Illuminate\Support\Facades\Hash;

class DataValidator
{
    public $res;
    public function __construct()
    {
        $this->res = ['msg'=>'err','status'=>false];
    }

    public function verifyData($value, $option, $user_data){
        switch ($option){
            case 'email': return $this->Email($value, $user_data);
            case 'name' :
            case 'surname': return $this->Name($value, $option, $user_data);
            case 'username': return $this->Username($value, $user_data);
            case 'nif': return $this->Nif($value, $user_data);
            case 'telephone': return $this->Phone($value, $user_data);
            case 'pass':
            case 'password': return $this->Password($value, $user_data);
            default:
                return ['msg'=>'Opción inválida', 'status'=>false];
        }
    }

    private function Password($pass, $userData){
        if(!$this->checkLen($pass,6)){
            $this->res['msg']='La constraseña debe contener al menos 6 caracteres.';
            return $this->res;
        }
        if(Hash::check($pass,$userData->pass)){
            $this->res['msg']='Las nueva contraseña es idéntica a la anterior.';
        }
        $this->res['status'] = true;
        return $this->res;
    }
    private function Phone($phone, $userData){
        if(!$this->checkPhones($phone) || !$this->checkLen($phone,9)){
            $this->res['msg'] = 'El valor introducido no está bien formado.';
            return $this->res;
        }
        if($phone === $userData->telephone){
            $this->res['msg'] = 'El nuevo valor es identico al anteior.';
            return $this->res;
        }
        $this->res['status'] = true;
        return $this->res;
    }
    private function Nif($nif, $userData){
        $this->res=['msg'=>'', 'status'=>false];

        $value = $this->checkNIF($nif);
        if(!$value['status']){
            $this->res['msg'] = $value['msg'];
            return $this->res;
        }
        if($nif === $userData->nif){
            $this->res['msg'] = 'El nuevo valor es idéntico al antarior.';
            return $this->res;
        }
        $this->res['status'] = true;
        return $this->res;
    }
    private function Username($username, $userData){
        if(!($this->checkLen($username, 4))){
            $this->res['msg'] = 'El nombre de usuario debe contener al menos 4 caracteres.';
            return $this->res;
        }
        if($username === $userData->username){
            $this->res['msg'] = 'El nuevo valor es idéntico al anterior.';
            return $this->res;
        }
        $this->res['status'] = true;
        return $this->res;
    }
    private function Name($name, $option, $userData){

        if(!$this->checkNames($name)){
            $this->res['msg'] = 'El nuevo valor contiene caracteres inválidos.';
            return $this->res;
        }
        if($name === $userData->$option){
            $this->res['msg'] = 'El nuevo valor es identico al anteior.';
            return $this->res;
        }
        $this->res['status'] = true;
        return $this->res;
    }
    private function Email($email, $userData){
        if(!$this->checkEmail($email)){
            $this->res['msg'] = 'Email mal formado.';
            return $this->res;
        }
        if($email === $userData->email){
            $this->res['msg'] = 'El nuevo email es igual al anterior.';
            return $this->res;
        }
        $this->res['status'] = true;
        return $this->res;
    }


    //TODO: terminar DNI y NIF
    public function checkNIF($value){
        $this->res = ['msg'=>null,'status'=>false];
        $DNI = ['T','R','W','A','G','M','Y','F','P','D','X','B','N','J','Z','S','Q','V','H','L','C','K','E'];
        if(!(strlen($value)>1)){
            $this->res['msg'] = 'El valor debe contener al menos 1 número y una letra.';
            return $this->res;
        }
        if(strlen($value) > 9){
            $this->res['msg'] = 'El valor excede el número de caracteres.';
            return $this->res;
        }
        //dd(in_array(strtoupper(substr($value,-1)),$DNI));
        if(!in_array(strtoupper(substr($value,-1)),$DNI)){
            $this->res['msg'] = 'El caracter de comprobación no es correcto';
            return $this->res;
        }
        return ['msg'=>'', 'status'=>true];
    }
    public function checkEmail($value){
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
    public function checkNames($value){
        return preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚÜ' ]*$/",$value);
    }
    public function checkPhones($value){
        return preg_match("/^[0-9-' ]*$/",$value);
    }
    public function checkLen($value, $len){
        return (strlen($value) >= $len);
    }
}
