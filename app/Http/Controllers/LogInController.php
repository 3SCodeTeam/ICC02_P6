<?php


namespace App\Http\Controllers;


use App\Models\Students;
use App\Models\Teachers;
use App\Models\UsersAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LogInController extends Controller
{
    public static function end(){
        @session_destroy();
        return view('login.blade.php',['errorMsg'=>'Se ha cerrado la sesión.']);
    }

    public static function new(){
        return view('login.blade.php');
    }
    public static function post(Request $request){
        $form_data=['name'=>$request->input('username'), 'email'=>$request->input('email'),'pass'=>$request->input('pass'),'type'=>$request->input('rol_option')];
        $user_data = self::getUserData($form_data);

        if($user_data->len = 0){ return self::error('La combinación usuario, email, password no existe.');}
        if(!self::checkPassword($form_data,$user_data)){return self::error('La combinación usuario, email, password no existe.');}
        return self::callUserTemplate($form_data, $user_data);
        //require_once('modules/login.class.php');
        //$controller = new LogInChecker();
        //$controller -> checkUser($form_data);
    }
    public static function error(string $errmsg=NULL){
        return view('login.blade.php',['errmsg'=>$errmsg]);
    }

    private static function checkPassword($form_data, $user_data){
        switch ($form_data['type']){
            case 'admin': return Hash::check($form_data['pass'],$user_data['password']);
            default: return Hash::check($form_data['pass'],$user_data['pass']);
        }
    }

    private static function callUserTemplate($form_data, $user_data){
        switch($form_data['type']){
            case 'student':
                return view('login.blade.php',['UserId'=> $user_data->id]);
                //$route = new Router('student', 'start');
                //break;
            case 'admin':
                return view('admin.blade.php',['UserId'=> $user_data->id_user_admin]);
                //$route = new Router('admin', 'start');
                //break;
            case 'teacher':
                return view('teacher.view.blade.php',['UserId'=> $user_data->id_teacher]);
                //$route = new Router('teacher', 'start');
                //break;
            default:
                return self::error('Tipo de usuario desconocido.');
        }
    }

    private static function getUserData($form_data){
        switch($form_data['type']){
            case 'admin':
                $module = new UsersAdmin();
                return $module->getByUsername($form_data['name']);
            case 'teacher':
                $module = new Teachers();
                return $module->getByEmail($form_data['email']);
            default:
                $module = new Students();
                return $module->getByUsername($form_data['name']);
        }
    }

    //TODO: CONTROL DE SESIÓN EN LARAVEL.
    /*
    private function checkUser(){
        $user_data = $this->getUserData();
        //var_dump($this->user_data);
        if($this->user_data != 0){
            if($this->userExist()&&$this->passMatch()){
                $_SESSION['token']=password_hash($_COOKIE['PHPSESSID'], PASSWORD_BCRYPT);
                switch ($this->tipo) {
                    case 'student':
                        $_SESSION['sql_user_id']=$this->user_data[0]->id;
                        break;
                    case 'admin':
                        $_SESSION['sql_user_id']=$this->user_data[0]->id_user_admin;
                        break;
                }
                $_SESSION['user_data']= $this->user_data[0];
                @$_SESSION['user_data']->pass ='';
                return $this->callUserTemplate();
            }
        }
        return $this->errorMsg('Usuario y/o contraseña incorrectos.');
    }
    */
}
