<?php


namespace App\Http\Controllers;


use App\Models\Students;
use App\Models\Teachers;
use App\Models\UsersAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use function session as sessionAlias;

class LogInController extends Controller
{
    public static function end(){
        sessionAlias()->forget(['sql_user_id', 'user_role']);
        return view('login',['msg'=>'Se ha cerrado la sesión.']);
    }

    public static function new(string $msg=null){
        return view('login',['msg' => $msg]);
    }

    public static function post(Request $request){
        $form_data = $request->except('_token');
        $user_data = self::getUserData($form_data);

        if($user_data->len < 1){ return self::error('La combinación usuario, email, password no existe.');}

        if(!self::checkPassword($form_data, $user_data->res[0])){
            return self::error('La combinación usuario, email, password no existe.');
        }
        return self::callUserTemplate($form_data, $user_data->res[0], $request);
    }

    public static function error(string $msg=NULL){
        return view('login',['msg'=>$msg]);
    }

    private static function checkPassword($form_data, $user_data): bool
    {
        switch ($form_data['type']){
            case 'admin': return Hash::check($form_data['pass'], $user_data->password);
            default: return Hash::check($form_data['pass'], $user_data->pass);
        }
    }

    private static function callUserTemplate($form_data, $user_data, Request $req){

        switch($form_data['type']){
            case 'student':
                $req->session()->put('sql_user_id', $user_data->id);
                $req->session()->put('user_role', 'student');
                return redirect()->route('student', ['start']);
            case 'admin':
                $req->session()->put('sql_user_id', $user_data->id_user_admin);
                $req->session()->put('user_role', 'admin');
                return redirect()->route('admin', ['start']);
            case 'teacher':
                $req->session()->put('sql_user_id', $user_data->id_teacher);
                $req->session()->put('user_role', 'teacher');
                return redirect()->route('teacher', ['start']);
            default:
                return self::error('Tipo de usuario desconocido.');
        }
    }

    private static function getUserData($form_data){
        switch($form_data['type']){
            case 'admin':
                $model = new UsersAdmin();
                //$model->getByUsername($form_data['username']);
                $model -> getByUsernameEmail($form_data['username'], $form_data['email']);
                break;
            case 'teacher':
                $model = new Teachers();
                $model->getByEmail($form_data['email']);
                break;
            default:
                $model = new Students();
                //$model->getByUsername($form_data['username']);
                $model -> getByUsernameEmail($form_data['username'], $form_data['email']);
        }
        return $model->data;
    }
}
