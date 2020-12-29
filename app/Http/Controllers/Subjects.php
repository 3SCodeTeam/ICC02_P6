<?php


namespace App\Http\Controllers;


use App\Models\Exams;
use App\Models\JoinQueries;
use App\Models\Teachers;
use App\Models\Works;
use App\Utils\MiscTools;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Object_;
use phpDocumentor\Reflection\Types\Self_;
use SebastianBergmann\CodeCoverage\Driver\WriteOperationFailedException;

class Subjects extends Controller
{
    /*Todos estos métodos se pueden llamar desde Teacher y Admin
    * Necesitan incluir un control teacher/admin.
    */

    //Creación de un trabajo/examen de una clase
    public static function create(Request $req, $id_class, $msg = null){
        $role = $req->session()->get('user_role');
        $values = MiscTools::getClassRelatedIds($id_class, 'class');

        //Datos de la vista.
        $jMod = new JoinQueries();
        $class_data = $jMod->getAllClassDatabyId($id_class);
        $course = MiscTools::getCourseDataByIdClass($id_class);

        if($role === 'teacher'){
            $teacherId = $req->session()->get('sql_user_id');
            $user_data = MiscTools::getTeacherData($teacherId);
            return view('teacher', ['selectedMenu'=>'subjectsCreate', 'course'=>$course, 'id_class'=>$id_class, 'user_data'=>$user_data, 'class_data'=>$class_data->res[0], 'msg'=>$msg]);
        }else{
            $user_data = MiscTools::getTeacherData($values['id_teacher']);
            return view('admin', ['selectedMenu'=>'subjectsCreate', 'id_class'=>$id_class, 'course'=>$course, 'user_data'=>$user_data, 'class_data'=>$class_data->res[0], 'msg'=>$msg]);
        }
    }

    //Listado de trabajos y exámenes de una clase
    public static function subjects(Request $req, $id_class, $msg=null){

        //Eliminamos la variable global para volver a hacer la selección.
        if($req->session()->exists('selectedSubjects')){
            $req->session()->remove('selectedSubjects');
        }

        $id_values = MiscTools::getClassRelatedIds($id_class, 'class');
        $role = $req->session()->get('user_role');

        $jMod = new JoinQueries();
        $class_data = $jMod ->getAllClassDatabyId($id_class);
        $course = MiscTools::getCourseDataByIdClass($id_class);

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
        switch ($role){
            case 'admin':
                $user_data = MiscTools::getTeacherData($id_values['id_teacher']);
                return view ('admin', ['selectedMenu'=>'subjects', 'id_class'=>$id_class, 'course'=>$course,'class_data'=>$class_data->res[0] ,'user_data'=>$user_data, 'msg'=>$msg, 'subjects'=>$subjects]);
            case 'teacher':
            default:
                $teacherId = $req->session()->get('sql_user_id');
                $user_data = MiscTools::getTeacherData($teacherId);
                return view('teacher', ['selectedMenu'=>'subjects', 'id_class'=>$id_class, 'course'=>$course, 'class_data'=>$class_data->res[0] ,'user_data'=>$user_data, 'msg'=>$msg, 'subjects'=>$subjects]);
        }

    }

    //Gestiona el post de la creación de un trabajo o exámen nuevo.
    public static function subjectsPost(Request $req, $id_class){
        $values = $req->only('name', 'date', 'time', 'type', 'description');
        $msg=null;

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

    //Actualizar las notas de un estudiante. Solo teacher, admin lo hace en details.
    public static function  subjectMarks(Request $req, $id_class, $id_student){
        $post = $req->except(['_token','submit']);

        //Determinar que notas traen valor null.
        $values = MiscTools::postNullRemove($post);

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

    //Actualizar/borrar los trabajos y exámenes.
    public static function update(Request $req, $id_class, $msg=null){
        $role = $req->session()->get('user_role');

        if(!$req->session()->exists('selectedSubjects')){
            $values = $req->except(['_token', 'action']);
            $action = $req->get('action');
            $req->session()->put(['selectedSubjects'=>$values]);
        }else{
            $values=$req->session()->get('selectedSubjects');
            $action = 'update';
        }

        if(count($values)<1){ //Si el post viene vaccío.
            return self::subjects($req, $id_class, $msg="No se ha seleccionado ningún elemento.");
        }

        //Datos para las vistas
        $course = MiscTools::getCourseDataByIdClass($id_class);
        $class_data = MiscTools::getClassData($id_class);
        $id_values = MiscTools::getClassRelatedIds($id_class, 'class');

        switch($action){
            case 'delete':
                foreach ($values as $name=>$type){
                    $name = self::getSubjectName($name);
                    self::deleteSubject($id_class, $name, $type);
                }
                $msg="Se han eliminado los elementos seleccionados.";
                return self::subjects($req, $id_class, $msg);
            case 'update':
            default:
                $subjects = self::getSubjectsRequested($values, $id_class);

                if($role === 'teacher'){
                    $teacherId = $req->session()->get('sql_user_id');
                    $user_data = MiscTools::getTeacherData($teacherId); //Se necesita el user_data para la vista teacher
                    return view('teacher', ['selectedMenu'=>'subjectsUpdate', 'course'=>$course, 'user_data'=>$user_data, 'class_data' => $class_data, 'id_class'=>$id_class, 'selectedSubjects' => $subjects, 'msg'=>$msg]);
                }else{
                    $user_data = MiscTools::getTeacherData($id_values['id_teacher']);
                    return view('admin', ['selectedMenu'=>'subjectsUpdate', 'course'=>$course,'user_data'=>$user_data, 'class_data' => $class_data, 'id_class'=>$id_class, 'selectedSubjects' => $subjects, 'msg'=>$msg]);
                }
        }
    }
    public static function updatePost(Request $req, $id_class){
        $post = MiscTools::postNullRemove($req->except(['_token', 'type', 'subject_name']));
        $type = $req->only('type');
        $type=$type['type'];
        $subject_name = $req->only('subject_name');

        switch($type){
            case 'exam':
                $mod = new Exams();
                break;
            case 'work':
            default:
                $mod = new Works();
        }

        $data = [];
        foreach ($post as $k=>$v){
            switch($k){
                case 'name':
                    $mod->getDistinctByIdClassName($id_class, $v);
                    if ($mod->data->len > 0) {
                        return self::update($req, $id_class, 'Ya existe una tarea con ese nombre.');
                    }
                    $data[$k] = $v;
                    break;
                case 'date':break;
                case 'time':
                    $data['deadline'] = $post['date'].' '.$post['time'];
                    break;
                default:
                    $data[$k] = $v;
            }
        }
        if($mod -> updateMultipleValuesByName($data, ['name'=>$subject_name, 'id_class'=>$id_class])<1){
            return self::update($req, $id_class, 'No hay datos que actualizar.');
        };

        //Solución ad hoc para el problema de las tareas que se identifican por el nombre.
        if(isset($post['name'])){
            $tempData = $req->session()->get('selectedSubjects');
            $newData =[];
            foreach ($tempData as $k => $v){
                if($v === $type){
                    $t = explode(';',$k);
                    if($t[1] === str_replace(' ','_',$subject_name['subject_name'])){
                        $n = $type.';'.str_replace(' ','_',$post['name']);
                        $newData[$n]=$type;
                    }else{
                        $newData[$k]=$v;
                    }
                }else{
                    $newData[$k]=$v;
                }
            }
            $req->session()->put(['selectedSubjects'=>$newData]);
        }

        return self::update($req, $id_class, 'Datos actualizados.');
    }

    /*FUNCIONES AUXILIARES*/
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
        if($mod->data->len <1){
            return null;
        }
        return $mod->data->res[0];
    }
    //DEVUELVE UN ARRAY CON LOS DATOS DE LOS SUBJECTS PASADO POR $VALUES
    private static function getSubjectsRequested($values, $id_class){
        $subjects = [];
        foreach ($values as $k=>$v){
            $data = self::getSubjectData($id_class, $k, $v);
            if(isset($data)){
                $date = date_create($data->deadline);
                $time = date_format($date,"h:m");
                $date = date_format($date,'Y-m-d');
                $subjects[] = ['name' => $data->name, 'date'=>$date, 'time'=>$time, 'description'=>$data->description, 'type'=>$v];
            }
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
    //OBTIENE LOS DATOS DEL PROFESOR DE UNA CLASE. MOVER A MISCTOOLS
    //OBTIENE LOS DATOS DE UNA CLASE. MOVER A MISCTOOLS
}
