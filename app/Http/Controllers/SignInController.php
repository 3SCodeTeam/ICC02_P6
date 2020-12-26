<?php


namespace App\Http\Controllers;



use App\Models\Notifications;
use App\Models\Students;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class SignInController extends Controller
{
    //public static function new(){}
    public static function post(Request $request){
        //COMPROBACIÓN
        if(self::checkUserName($request->input('username'))){return self::error('Este nombre de usuario no está disponible.');}
        if(self::checkEmail($request->input('email'))){return self::error('Ya existe un usuario con esta cuenta de correo.');}
        if(self::checkNif($request->input('nif'))){return self::error('Este NIF ya ha sido registrado.');}
        if(self::checkNifForm($request->input('nif'))){return self::error('NIF mal formado.');}
        if(self::checkPassLen($request->input('password'))){return self::error('La contraseña debe tener al menos 6 caracteres');}
        if(self::checkPassMatch($request->input('password'), $request->input('password_check'))){return self::error('Las contraseñas no coinciden');}

        //REGISTRO
        $date = new DateTime();
        $date = $date->format('Y-m-d h:m:s');
        //$stm = 'INSERT INTO students (date_registered, email, name, nif, pass, surname, telephone, username) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        $module = new Students();
        $module->insertValues($date, $request->input('email'), $request->input('name'),
        $request->input('nif'), Hash::make($request->input('password')), $request->input('surname'),
            $request->input('telephone'), $request->input('username'));
        $res = $module->data->affected_rows;
        if($res < 1){
            self::error('Error al escribir en la base de datos.');
        }
        $module->getByUsername($request->input('username'));
        $id_student = $module->data->res[0]->id;

        /*NOTIFICACIONES MODIFICACIÓN INTRODUCIDA Version 2.3*/
        $notifications = $request->only('work', 'exam', 'continuous assessment', 'final');
        $notifications = self::setNotificationsData($notifications);
        $nMod = new Notifications();
        $nMod->insertValues($id_student, $notifications['work'], $notifications['exam'], $notifications['continuous assessment'], $notifications['final']);

        //FIN
        return view('login',['msg'=>'Bienvenido '.$request->input('name').'. Ya puedes inicar sesión.']);
    }
    public static function error($msg){
        return view('signin',['msg'=>$msg]);
    }


    private static function  checkNifForm($nif){
        if(strlen($nif)>9){
            return true;
        }
        if(!ctype_alpha(substr($nif,-1))){
            return true;
        }
        return false;
    }
    private static function checkPassLen($pass){
        if(strlen($pass) < 6){
            return true;
        }
        return false;
    }
    private static function checkPassMatch($pass1,$pass2){
        if($pass1 != $pass2){
            return true;
        }
        return false;
    }

    private static function checkUserName($username){
        $model = new Students();
        $model->getByUsername($username);
        $res = $model->data->len;
        if($res > 0){
            return true;
        }
        return false;
    }
    private static function checkEmail($email){
        $model = new Students();
        $model->getByEmail($email);
        $res = $model->data->len;
        if($res > 0){
            return true;
        }
        return false;
    }
    private static function checkNif($nif){
        $model = new Students();
        $model->getByNif($nif);
        $res = $model->data->len;
        if($res > 0) {
            return true;
        }
        return false;
    }
    private static function setNotificationsData(array $selectedNotifications){
        $notifications = ['work'=>0, 'exam'=>0, 'continuous_assessment'=>0, 'final'=>0];
        foreach ($selectedNotifications as $k=>$v){
            $notifications[$k] = $v;
        }
        return $notifications;
    }
}
