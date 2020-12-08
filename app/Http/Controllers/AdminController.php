<?php


namespace App\Http\Controllers;

use App\Models\Courses;

use App\Models\Teachers;
use App\Models\UsersAdmin;

use App\Utils\DataValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use stdClass;


class AdminController extends Controller
{
    public static function start(Request $req){
        //Lo enviamos al perfil.
        return self::profile($req);
    }
    public static function profile(Request $req){
        $userId = $req->session()->get('sql_user_id');
        $mod = new UsersAdmin();
        $mod->getById($userId);
        $res = $mod->data->res;
        $user_data = self::getAdminUserData($res);
        return view('admin', ['user_data'=>$user_data, 'selectedMenu'=>'profile']);
    }
    public static function profilePost(Request $req){
        $userId = $req->session()->get('sql_user_id');
        $option = $req->input('user_data_option');
        $value = $req->input('value');

        $mod = new UsersAdmin();
        $mod->getById($userId);
        $user_data = $mod->data->res[0];

        $checker = new DataValidator();
        $res = $checker->verifyData($value, $option, $user_data);
        if(!$res['status']){
            return view('admin',['selectedMenu'=>'profile','user_data'=>$user_data, 'msg'=>$res['msg']]);
        }

        //TODO: verificar duplicados en db username, email

        $res = self::updateDB($value,$option,$userId, $mod);
        if(!$res['status']){
            return view('admin',['selectedMenu'=>'profile','user_data'=>$user_data, 'msg'=>$res['msg']]);
        }

        $mod->getById($userId);
        return view('admin',['selectedMenu'=>'profile', 'user_data'=>$mod->data->res[0], 'msg'=>$res['msg']]);
    }
    public static function teacher(){
        $mod = new Teachers();
        $mod ->getAll();
        $teachers_data = $mod->data->res;

        return view('admin', ['selectedMenu'=>'teachers', 'teachers_data'=>$teachers_data]);
    }
    public static function teachersPost(Request $req){
        $mod = new Teachers();
        $mod->getAll();
        $data =['name'=>'teachers_data', 'values'=>$mod->data->res];
        $values = $req->only(['name','nif','surname','telephone','email','password']);

        //Validar datos del formulario.
        foreach ($values as $key=>$v){
            $checker = new DataValidator();
            switch ($key){
                case 'name':
                case 'surname':
                    if(!$checker->checkNames($v)){
                        return self::adminError('admin','El nombre contiene caracteres no validos.', 'teachers',$data);
                    };
                    break;
                case 'nif':
                    $res=$checker->checkNIF($v);
                    if(!$res['status']){return self::adminError('admin',$res['msg'],'teachers',$data);}
                    break;
                case 'password':
                    if(!$checker->checkLen($v,6)){return self::adminError('admin','La contraseña debe tener al menos 6 caracteres.','teachers',$data);}
                    break;
                case 'telephone':
                    if(!$checker->checkPhones($v)){return self::adminError('admin','Teléfono mal formado.','teachers',$data);}
                    break;
                case 'email':
                    if(!$checker->checkEmail($v)){return self::adminError('admin','Email mal formado.','teachers',$data);}
                    break;
            }
        }
        //Verificar datos UNIQUE
        foreach ($values as $key=>$v){
            switch ($key){
                case 'nif':
                    if(in_array($v,$data['values'])){return self::adminError('admin', 'El NIF introducido ya existe en la base de datos.','teachers',$data);}
                    break;
                case 'email':
                    if(in_array($v,$data['values'])){return self::adminError('admin', 'El email introducido ya existe en la base de datos.','teachers',$data);}
            }
        }
        //Insertar datos en DB
        $mod->insertValues($values['name'],$values['surname'],$values['telephone'],$values['nif'],$values['email'],Hash::make($values['password']));
        if($mod->data->affected_rows>0){
            $mod->getAll();
            $teachers_data = $mod->data->res;
            return view('admin',['msg'=>'¡Profesor registrado!', 'selectedMenu'=>'teachers','teachers_data'=>$teachers_data]);
        }
        return self::adminError('admin', 'Error de acceso a la base de datos.','teachers',$data);
    }
    public static function courses(){
        $mod = new Courses();
        $mod->getAll();
        $courses_data = $mod->data->res;

        return view('admin', ['courses_data'=>$courses_data, 'selectedMenu'=>'courses']);
    }
    public static function coursesPost(Request $req){
        $values = $req->only(['name', 'date_start', 'date_end', 'active', 'description']);
        $mod = new Courses();
        $mod->getAll();
        $courses_data = $mod->data->res;

        if(self::in_ArrayObject($values['name'], $courses_data, 'name')){
           return view('admin', ['selectedMenu'=>'courses', 'courses_data'=>$courses_data, 'msg'=>'Este nombre del curso ya existe.']);
        }
        if($values['date_start'] >= $values['date_end']){
            return view('admin', ['selectedMenu'=>'courses', 'courses_data'=>$courses_data, 'msg'=>'La fecha de inicio no puede ser posterior o igual a la de finalización.']);
        }
        $mod->insertValues($values['name'],$values['description'],$values['date_start'],$values['date_end'],$values['active']);
        if($mod->data->affected_rows > 0){
            $mod->getAll();
            $courses_data=$mod->data->res;
            return view('admin', ['selectedMenu'=>'courses', 'courses_data'=>$courses_data, 'msg'=>'¡Curso añadido!.']);
        }
        return view('admin', ['selectedMenu'=>'courses', 'courses_data'=>$courses_data, 'msg'=>'Error de acceso a la base de datos.']);

    }
    public static function classes(){}
    public static function classesPost(){}
    public static function delete(){}

    //AUX FUNCTIONS
    private static function getAdminUserData($res){
        $user_data = new stdClass();
        $user_data->name = $res[0]->name;
        $user_data->username = $res[0]->username;
        $user_data->email = $res[0]->email;
        return $user_data;
    }

    private static function updateDB($newValue, $attr, $userId, $mod)
    {
        $mod->updateValueById(self::checkOption($attr), $newValue, $userId);
        if(!$mod->data->status || !($mod->data->affected_rows > 0)){
            return ['msg'=>'Error al actulizar el valor en la base de datos.', 'status'=>false];
        }
        return ['msg'=>'¡Valor actualizado!', 'status'=>true];
    }
    private static function checkOption($option){
        switch($option){
        }
        return $option;
    }
    private static function adminError($view, $msg, $menu, $data){
        return view($view, ['selectedMenu'=>$menu, 'msg'=>$msg, $data['name']=>$data['values']]);
    }
    private static function in_ArrayObject($value, $obj, $key){
        foreach($obj as $item){
            foreach ($item as $k=>$v){
                if($k == $key){
                    if($v == $value){return true;}
                }
            }
        }
        return false;
    }

}
