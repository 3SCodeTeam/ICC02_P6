<?php


namespace App\Http\Controllers;


use App\Models\Students;
use App\Utils\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class StudentController extends Controller
{
    public static function start(Request $req){/*PRIMERA CARGA DESDE EL LOGIN*/
        //Lo enviamos al perfil.
        return self::profile($req);
    }
    public static function profile(Request $req){/*SECCION PROFILE*/
        $mod = new Students();
        $mod->getById($req->get('sql_user_id'));
        $user_data = $mod->data->res[0];
        return view('student',['selectedMenu'=>'profile', 'user_data'=>$user_data]);
    }
    public static function profilePost(Request $req){/*ACTUALIZACIÓN DATOS PROFILE*/
        $mod = new Students();
        $mod->getById(intval($req->session()->get('sql_user_id')));
        $user_data =  $mod->data->res[0];
        $res = self::updateData($req->input('value'),$req->input('user_data_option'), $user_data);
        if(!$res['status']){
            return view('student',['selectedMenu'=>'profile','user_data'=>$user_data, 'msg'=>$res['msg']]);
        }
        $mod->getById($user_data->id);
        return view('student',['selectedMenu'=>'profile', 'user_data'=>$mod->data->res[0], 'msg'=>$res['msg']]);
    }
    public static function enrollment(){/*SECCION ENROLLMENT*/}
    public static function enrollmentPost(Request $req){/*DATOS DEL FORMULARIO DE MATRICULACIÓN*/}
    public static function schedule(){/*SECCIÓN HOARIO PRIMERA CARGA DESDE EL MENÚ*/}
    public static function wSchedule(){/*SECCION HORARIO SEMANAL CARGA DESDE EL MENÚ*/}
    public static function dSchedule(){/*SECCION HORARIO DIARIO CARGA DESDE EL MENÚ*/}
    //TODO: horario día concreto
    //TODO: horario semana concreta
    //TODO: horario mes concreto
    public static function record(){/*SECCIÖN RECORD CARGA DESDE EL MENÚ*/}
    public static function recordDetail($id_class){/*SECCIÓN DETALLES ASIGNATURA*/}

    private static function updateData($value, $option, $user_data){
        switch ($option){
            case 'email': return self::Email($value, $user_data);
            case 'name' :
            case 'surname': return self::Name($value, $option, $user_data);
            case 'username': return self::Username($value, $user_data);
            case 'nif': return self::Nif($value, $user_data);
            case 'telephone': return self::Phone($value, $user_data);
            case 'password': return self::Password($value, $user_data);
            default:
                return ['msg'=>'Opción inválida', 'status'=>false];
        }
    }

    private static function updateDB($newValue, $attr, $userId)
    {
        $mod = new Students();
        $mod->updateValueById($attr, $newValue, $userId);
        if(!$mod->data->status || !($mod->data->affected_rows > 0)){
            return ['msg'=>'Error al actulizar el valor en la base de datos.', 'status'=>false];
        }
        return ['msg'=>'¡Valor actualizado!', 'status'=>true];
    }
    private static function Password($pass, $userData){
        $res = ['msg'=>'','status'=>false];
        if(!Utils::checkLen($pass,6)){
            $res['msg']='La constraseña debe contener al menos 6 caracteres.';
            return $res;
        }
        if(Hash::check($pass,$userData->pass)){
            $res['msg']='Las nueva contraseña es idéntica a la anterior.';
        }
        return self::updateDB($pass,'pass',$userData->id);
    }
    private static function Phone($phone, $userData){
        $res = ['msg'=>'','status'=>false];
        if(!Utils::checkPhones($phone) || !Utils::checkLen($phone,9)){
            $res['msg'] = 'El valor introducido no está bien formado.';
            return $res;
        }
        if($phone === $userData->telephone){
            $res['msg'] = 'El nuevo valor es identico al anteior.';
            return $res;
        }
        return self::updateDB($phone,'telephone',$userData->id);
    }
    private static function Nif($nif, $userData){
        $res=['msg'=>'', 'status'=>false];
        $mod = new Students();
        $value = Utils::checkNIF($nif);
        if(!$value['status']){
            $res['msg'] = $value['msg'];
            return $res;
        }
        if($nif === $userData->nif){
            $res['msg'] = 'El nuevo valor es idéntico al antarior.';
            return $res;
        }
        $mod->getByNif($nif);
        if($mod->data->len > 0){
            $res['msg'] = 'El nuevo NIF ya existe en la base de datos.';
            return $res;
        }
        return self::updateDB($nif,'nif',$userData->id);
    }
    private static function Username($username, $userData){
        $res = ['msg'=>'','status'=>false];
        $mod = new Students();
        if(!(Utils::checkLen($username, 4))){
            $res['msg'] = 'El nombre de usuario debe contener al menos 4 caracteres.';
            return $res;
        }
        if($username === $userData->username){
            $res['msg'] = 'El nuevo valor es idéntico al anterior.';
            return $res;
        }
        $mod->getByUsername($username);
        if($mod->data->len > 0){
            $res['msg'] = 'El nuevo nombre de usuario ya existe en la base de datos.';
            return $res;
        }
        return self::updateDB($username,'username',$userData->id);
    }
    private static function Name($name, $option, $userData){
        $res = ['msg'=>'','status'=>false];
        if(!Utils::checkNames($name)){
            $res['msg'] = 'El nuevo valor contiene caracteres inválidos.';
            return $res;
        }
        if($name === $userData->$option){
            $res['msg'] = 'El nuevo valor es identico al anteior.';
            return $res;
        }
        return self::updateDB($name, $option, $userData->id);
    }
    private static function Email($email, $userData){
        $res = ['msg'=>'','status'=>false];
        $mod = new Students();
        if(!Utils::checkEmail($email)){
            $res['msg'] = 'Email mal formado.';
            return $res;
        }
        if($email === $userData->email){
            $res['msg'] = 'El nuevo email es igual al anterior.';
            return $res;
        }
        $mod->getByEmail($email);
        if($mod->data->len > 0){
            $res['msg'] = 'El nuevo email ya existe en la base de datos.';
            return $res;
        }
        return self::updateDB($email,'email',$userData->id);
    }
}
