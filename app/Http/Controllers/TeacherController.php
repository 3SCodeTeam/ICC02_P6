<?php


namespace App\Http\Controllers;

use App\Models\Exams;
use App\Models\JoinQueries;
use App\Models\Students;
use App\Models\Teachers;
use App\Models\Works;
use App\Utils\MarkSTools;
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
    public static function subjectsPost(Request $req, $id_class){
        $values = $req->only('name', 'date', 'time', 'type', 'description');
        $msg=null;
        $teacherId = $req->session()->get('sql_user_id');
        $user_data = self::getTeacherData($teacherId);

        if(self::nameExist($values['name'],$values['type'],$id_class)){
            $msg = 'Este nombre ya se ha usado.';
            return self::subjects($req, $id_class, $msg);
        }
        //Generamos un array con los exámenes o trabajos para cada estudiante matriculado.
        $dataToInsert = self::getArrayOfSubjects($id_class,$values);
        $mod= new JoinQueries();
        $mod->insertMultiple($dataToInsert, $values['type']);
        $msg = 'Actividad creada.';

        return self::subjects($req, $id_class,$msg);
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
    private static function getArrayOfSubjects($id_class, $values){
        $data = [];
        $mod = new JoinQueries();
        $mod ->getStudentsByClass($id_class);
        $timestamp = $values['date'].' '.$values['time'];
        foreach ($mod->data->res as $s){
            $data[] =['id_class'=>$id_class, 'id_student'=>$s->id_student, 'name'=>$values['name'], 'deadline'=>$timestamp,'description'=>$values['description'], 'mark'=>-1];
        }
        return $data;
    }
    private static function nameExist($name, $type, $id_class){
        switch($type){
            case 'exams':
                $mod = new Exams();
                break;
            case 'works':
                $mod = new Works();
                break;
        }
        $mod ->getDistinctByIdClass($id_class);
        foreach ($mod->data->res as $r) {
            if($r->name === $name){
                return true;
            }
        }
        return false;
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
