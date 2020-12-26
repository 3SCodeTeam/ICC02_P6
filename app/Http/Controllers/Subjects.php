<?php


namespace App\Http\Controllers;


use App\Models\Exams;
use App\Models\JoinQueries;
use App\Models\Teachers;
use App\Models\Works;
use App\Utils\MiscTools;
use Illuminate\Http\Request;
use SebastianBergmann\CodeCoverage\Driver\WriteOperationFailedException;

class Subjects extends Controller
{
    public static function create(Request $req, $id_class, $msg = null){
        $role = $req->session()->get('user_role');

        //Datos de la clase.
        $jMod = new JoinQueries();
        $class_data = $jMod->getAllClassDatabyId($id_class);

        if($role === 'teacher'){
            $teacherId = $req->session()->get('sql_user_id');
            $user_data = self::getTeacherData($teacherId);

            return view('teacher', ['selectedMenu'=>'subjectsCreate', 'id_class'=>$id_class, 'user_data'=>$user_data, 'class_data'=>$class_data->res[0], 'msg'=>$msg]);
        }else{
            //TODO: LOGICA PARA ADMIN
        }
    }

    //CREA UN TRABAJO O EXAMEN NUEVO.
    public static function subjectsPost(Request $req, $id_class){
        $values = $req->only('name', 'date', 'time', 'type', 'description');
        $msg=null;
        $teacherId = $req->session()->get('sql_user_id');
        $user_data = self::getTeacherData($teacherId);

        if(self::nameExist($values['name'],$values['type'],$id_class)){
            $msg = 'Este nombre ya se ha usado.';
            return self::create($req, $id_class, $msg);
        }
        //Generamos un array con los exámenes o trabajos para cada estudiante matriculado y luego lo insertamos.
        $dataToInsert = self::getArrayOfSubjects($id_class,$values);
        $mod= new JoinQueries();
        $mod->insertMultiple($dataToInsert, $values['type']);
        $msg = 'Actividad creada.';

        return self::create($req, $id_class, $msg);
    }

    //ACTUALIZAR NOTAS
    public static function  subjectMarks(Request $req, $id_class, $id_student){
        $values = $req->except(['_token','submit']);

        if(count($values)<1){
            $msg = 'No se ha establecido ninguna nota.';
            return TeacherController::studentDetails($req, $id_class, $id_student, $msg);
        }
        $emod = new Exams();
        $wmod = new Works();
        foreach ($values as $k=>$v){
            $keys = explode(';', $k);
            $type = $keys[0];
            $id = $keys[1];
            switch ($type){
                case 'exam':
                    $emod->updateValueById('mark', $v, $id);
                    break;
                case 'work':
                    $wmod->updateValueById('mark', $v, $id);
                    break;
            }
        }
        $msg = 'Notas actualizadas.';
        return TeacherController::studentDetails($req, $id_class, $id_student, $msg);
    }
    //UPDATE SUBJECTS
    public static function update(Request $req, $id_class, $msg=null){
        $values = $req->except(['_token', 'action']);
        $action = $req->get('action'); //ACTION ES LA ACCIÓN SOLICITADA UPDATE O DELETE
        $teacherId = $req->session()->get('sql_user_id');
        $user_data = self::getTeacherData($teacherId); //NECEISTAMOS EL USER DATA PARA LA VISTA TEACHER
        $class_data = self::getClassData($id_class); //NECESITAMOS LOS DATOS DE LA CALSE EN ESTA VISTA

        if(count($values)<1){
            return TeacherController::subjects($req, $id_class, $msg="No se ha seleccionado ningún elemento.");

        }

        switch($action){
            case 'delete':
                foreach ($values as $name=>$type){
                    $name = self::getSubjectName($name);
                    self::deleteSubject($id_class, $name, $type);
                }
                return TeacherController::subjects($req, $id_class, $msg="Se han eliminado los elementos seleccionados.");
            case 'update':
            default:
                //Get data from the from the exams and works selected.
                $subjects = self::getSubjectsRequested($values, $id_class);
                return view('teacher', ['selectedMenu'=>'subjectsUpdate', 'user_data'=>$user_data, 'class_data' => $class_data, 'id_class'=>$id_class, 'selectedSubjects' => $subjects]);
        }
    }

    //FUNCIONES AUXILIARES
    private static function getSubjectName($name){
        $name = explode(';', $name);
        $name = $name[1];
        $name = str_replace("_", " ", $name); //Remplazar los '_' que introduce el request por ' '.
        return $name;
    }
    private static function deleteSubject($id_class, $name, $type){
        switch ($type){
            case 'exam':
                $mod = new Exams();
                break;
            case 'work':
            default:
                $mod = new Works();
        }
        $mod->deleteByIdClassName($id_class,$name);
        return $mod->data;
    }
    //DEVUELVE UN OBJETO CON EL NOMBRE Y LA DESCRIPCIÓN DE LOS TRABAJOS O EXAMENES DE UNA CLASE
    private static function getSubjectData($id_class, $name, $type){
        $name = self::getSubjectName($name);
        switch ($type){
            case 'exam': $mod = new Exams();break;
            case 'work':
            default:$mod = new Works();
        }
        $mod -> getDistinctByIdClassName($id_class, $name);
        return $mod->data->res[0];
    }
    //DEVUELVE UN ARRAY CON LOS DATOS DE LOS SUBJECTS PASADO POR $VALUES
    private static function getSubjectsRequested($values, $id_class){
        $subjects = [];
        foreach ($values as $k=>$v){
            $data = self::getSubjectData($id_class, $k, $v);
            $date = date_create($data->deadline);
            $time = date_format($date,"h:m");
            $date = date_format($date,'Y-m-d');
            $subjects[] = ['name' => $data->name, 'date'=>$date, 'time'=>$time, 'description'=>$data->description, 'type'=>$v];
        }
        return $subjects;
    }
    //DEVUELVE UN ARRAY DE TRABAJOS Y EXÁMENES POR CLASE.
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
    //COMPRUEBA SI EL NOMBRE INTRODUCIDO YA EXISTE
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
    //OBTIENE LOS DATOS DEL PROFESOR DE UNA CLASE.
    private static function getTeacherData($id){
        $mod = new Teachers();
        $mod -> getById($id);

        return MiscTools::safeUserData($mod->data->res[0]);
    }
    //OBTIENE LOS DATOS DE UNA CLASE
    private static function getClassData($id){
        $mod = new JoinQueries();
        $mod->getAllClassDatabyId($id);
        return $mod->data->res[0];
    }
}
