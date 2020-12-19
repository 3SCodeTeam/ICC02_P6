<?php


namespace App\Http\Controllers;


use App\Models\Exams;
use App\Models\JoinQueries;
use App\Models\Teachers;
use App\Models\Works;
use App\Utils\MiscTools;
use Illuminate\Http\Request;

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
            //LOGICA PARA ADMIN
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

    //FUNCIONES AUXILIARES
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
}
