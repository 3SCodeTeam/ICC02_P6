<?php


namespace App\Http\Controllers;


use App\Models\JoinQueries;
use App\Models\Teachers;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public static function start(Request $req){
        $teacherId = $req->session()->get('sql_user_id');
        $user_data = self::getTeacherData($teacherId);
    }
    public static function profile(Request $req){
        $teacherId = $req->session()->get('sql_user_id');
        $user_data = self::getTeacherData($teacherId);
        return view('teacher', ['selectedMenu'=>'profile','user_data'=>$user_data]);
    }

    public static function classes(Request $req){
        $msg=null;
        $teacherId = $req->session()->get('sql_user_id');

        $jmod = new JoinQueries();
        $data = $jmod->getClassesCoursesStudentsByTeacher($teacherId);

        $user_data = self::getTeacherData($teacherId);

        if($data->len < 1){
            $msg = 'No tienes clases programadas.';
        }

        return view('teacher', ['selectedMenu'=>'classes','msg'=>$msg,'classes'=>$data->res, 'user_data'=>$user_data]);
    }
    private static function safeUserData($user_data){
        $data=[];
        foreach ($user_data as $k => $v){
            if(!in_array($k, ['pass', 'password'])){
                $data[$k] = $v;
            }
        }
        return $data;
    }
    private static function getTeacherData($id){
        $mod = new Teachers();
        $mod -> getById($id);

        return self::safeUserData($mod->data->res[0]);
    }
}
