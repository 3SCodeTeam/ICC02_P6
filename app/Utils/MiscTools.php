<?php


namespace App\Utils;
use App\Models\Classes;
use App\Models\Courses;
use App\Models\JoinQueries;
use App\Models\Students;
use App\Models\Teachers;

class MiscTools
{
    public static function safeUserData($user_data){
        $data=[];
        foreach ($user_data as $k => $v){
            if(!in_array($k, ['pass', 'password'])){
                $data[$k] = $v;
            }
        }
        return $data;
    }
    public static function getClassRelatedIds($id, $type='class'): array
    {
        $values = ['id_course'=>'','id_class'=>'', 'id_teacher'=>''];
        $mod = new Classes();
        switch($type){
            case 'class':
                $mod -> getById($id);
                $values['id_class'] = $id;
                $values['id_course'] = $mod->data->res[0]->id_course;
                $values['id_teacher'] = $mod->data->res[0]->id_teacher;
                break;
            case 'course':
                $values['id_course'] = $id;
                $mod ->getByIdCourse($id);
                $values['id_class'] = [];
                $values['id_teacher'] = [];
                foreach ($mod->data->res as $r){
                    if(!in_array($r->id_class, $values['id_class'])){
                        $values['id_class'][] = $r->id_class;
                    }
                    if(!in_array($r->id_teacher, $values['id_teacher'])){
                        $values['id_teacher'][] = $r->id_teacher;
                    }
                }
                break;
            case 'teacher':
                $values['id_teacher'] = $id;
                $mod -> getByTeacher($id);
                $values['id_class'] = [];
                $values['id_course'] = [];
                foreach ($mod->data->res as $r){
                    if(!in_array($r->id_class, $values['id_class'])){
                        $values['id_class'][] = $r->id_class;
                    }
                    if(!in_array($r->id_course, $values['id_course'])){
                        $values['id_course'][] = $r->id_course;
                    }
                }
                break;
            default:
                $values = ['id_course'=>'','id_class'=>'', 'id_teacher'=>''];
        }
        return $values;
    }
    public static function getCourseData($courseId, $role='admin'){
        $mod = new Courses();
        $mod->getById($courseId);
        $course =$mod->data->res[0];

        $course=['role'=>$role, 'id_course'=>$course->id_course, 'name'=>$course->name, 'date_start'=>$course->date_start, 'date_end'=>$course->date_end];
        return $course;
    }
    public static function getCourseDataByIdClass($id_class){
        $mod = new Classes();
        $mod -> getById($id_class);
        $id_course = $mod->data->res[0]->id_course;

        return self::getCourseData($id_course);
    }
    public static function getStudentData($id_student){
        $mod = new Students();
        $mod->getById($id_student);
        $student = $mod->data->res[0];

        $student = ['id_student'=>$student->id, 'name'=>$student->name, 'surname'=>$student->surname, 'email'=>$student->email, 'telephone'=>$student->telephone];
        return $student;
    }
    public static function getTeacherData($id){
        $mod = new Teachers();
        $mod -> getById($id);

        return self::safeUserData($mod->data->res[0]);
    }
    public static function getClassData($id){
        $mod = new JoinQueries();
        $mod->getAllClassDatabyId($id);
        return $mod->data->res[0];
    }
    public static function inArray(string $value, array $data, string $field):bool{
        foreach ($data as $d){
            if($d[$field] === $value){
                return true;
            }
        }
        return false;
    }
}
