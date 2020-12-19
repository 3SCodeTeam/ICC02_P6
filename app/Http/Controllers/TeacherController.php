<?php


namespace App\Http\Controllers;

use App\Models\Exams;
use App\Models\JoinQueries;
use App\Models\Students;
use App\Models\Teachers;
use App\Models\Works;
use App\Utils\MarkSTools;
use App\Utils\MiscTools;
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
        $user_data = self::getTeacherData($teacherId);

        $jmod = new JoinQueries();
        $data = $jmod->getClassesCoursesStudentsByTeacher($teacherId);
        if($data->len < 1){
            $msg = 'No tienes clases programadas.';
        }

        return view('teacher', ['selectedMenu'=>'classes','msg'=>$msg,'classes'=>$data->res, 'user_data'=>$user_data]);
    }
    public static function students(Request $req, $id_class){//Devolver un listado de estudiantes por asignatura
        $msg=null;
        $teacherId = $req->session()->get('sql_user_id');
        $user_data = self::getTeacherData($teacherId);

        $jMod = new JoinQueries();
        $students=$jMod->getStudentsByClass($id_class);
        //dd($students->res);

        $marks = MarkSTools::getStudentsMarksByClass($id_class, $students->res);
        //dd($marks);

        return view('teacher', ['selectedMenu'=>'students', 'user_data'=>$user_data, 'students'=>$students->res, 'marks'=>$marks, 'id_class'=>$id_class]);
    }
    //Listado de asignaturas de una clase desde el menu Teacher.
    public static function subjects(Request $req, $id_class, $msg=null){
        $teacherId = $req->session()->get('sql_user_id');
        $user_data = self::getTeacherData($teacherId);

        $jMod = new JoinQueries();
        $class_data = $jMod ->getAllClassDatabyId($id_class);

        $wMod=new Works();
        $eMod = new Exams();
        $wMod->getDistinctByIdClass($id_class);
        $eMod->getDistinctByIdClass($id_class);

        $subjects = ['works'=>[], 'exams'=>[]];
        if($wMod->data->len > 0){
            $subjects['works'] = $wMod->data->res;
        }
        if($eMod->data->len > 0){
            $subjects['exams'] = $eMod->data->res;
        }

        return view('teacher', ['selectedMenu'=>'subjects', 'id_class'=>$id_class, 'class_data'=>$class_data->res[0] ,'user_data'=>$user_data, 'msg'=>$msg, 'subjects'=>$subjects]);
    }
    public static function studentDetails(Request $req, $id_class, $id_student){
        $msg=null;
        $teacherId = $req->session()->get('sql_user_id');
        $user_data = self::getTeacherData($teacherId);

        $eMod = new Exams();
        $wMod = new Works();
        $eMod->getByIdClassAndIdStudent($id_class, $id_student);
        $wMod->getByIdClassAndIdStudent($id_class, $id_student);

        $sMod = new Students();
        $sMod->getById($id_student);

        return view('teacher',['selectedMenu'=>'studentDetails', 'id_class'=>$id_class, 'user_data'=>$user_data, 'msg'=>$msg ,'works'=>$wMod->data->res, 'exams'=>$eMod->data->res, 'student'=>$sMod->data->res]);
    }
    private static function getTeacherData($id){
        $mod = new Teachers();
        $mod -> getById($id);

        return MiscTools::safeUserData($mod->data->res[0]);
    }
}
