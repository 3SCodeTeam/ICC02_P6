<?php


namespace App\Http\Controllers;


use App\Models\Classes;
use App\Models\Courses;
use App\Models\Exams;
use App\Models\JoinQueries;
use App\Models\Percentages;

use App\Models\Students;
use App\Models\Works;
use Illuminate\Http\Request;

class DetailsController extends Controller
{
    public static function studentsDetails($id_course, Request $req){
        $role = $req->session()->get('user_role');

        $mod = new JoinQueries();
        $students = $mod->getStudentsDataByCourse($id_course);

        $mod = new Courses();
        $mod->getById($id_course);
        $course = $mod->data->res[0];
        $course=['role'=>$role, 'id_course'=>$course->id_course, 'name'=>$course->name, 'date_start'=>$course->date_start, 'date_end'=>$course->date_end];

        return view('admin', ['selectedMenu'=>'studentsDetails', 'course'=>$course,'students'=>$students->res]);
    }
    public static function classesDetails($id_course, Request $req){
        $joinMod = new JoinQueries();

        $classes = $joinMod->getClassesAndTeachersByCourse($id_course);

        $course= self::getCourseData($id_course);

        return view('admin', ['selectedMenu'=>'classesDetails','course'=>$course, 'classes'=>$classes->res]);
    }
    public static function subjectsDetails($id, Request $req){
        $role = $req->session()->get('user_role');
        $mod = new Exams();
        $mod->getDistinctByIdClass($id);
        $exams = $mod->data->res;

        $mod = new Works();
        $mod->getDistinctByIdClass($id);
        $works = $mod->data->res;

        $mod = new Percentages();
        $mod->getByIdClass($id);
        $percentages = $mod->data->res;

        $mod = new Classes();
        $mod ->getById($id);
        $courseId = $mod->data->res[0]->id_course;
        $course = self::getCourseData($courseId, $role);

        return view('details', ['role'=>$role, 'classes' => $mod->data->len, 'selectedMenu'=>'subjectsDetails', 'course'=>$course, 'exams'=>$exams, 'works'=>$works, 'percentage'=>$percentages]);
    }
    public static function subjectsOfStudent($id_course, $id_student, $subject=null, $msg=null){
        $course = self::getCourseData($id_course);
        $student = self::getStudentData($id_student);

        $subjects = self::getSubjectsArray($id_course, $id_student);

        return view('admin', ['selectedMenu'=>'subjectsOfStudent', 'course'=>$course, 'student'=>$student, 'subjects'=>$subjects, 'subject'=>$subject, 'msg'=>$msg]);
    }
    public static function record($type, $id_subject, $msg=null){

        switch ($type){
            case 'exam':
                $mod = new Exams();
                break;
            case 'work':
            default:
                $mod = new Works();
                break;
        }
        $mod->getById($id_subject);

        $subject = self::getSubjectRequestedData($mod->data->res[0],$type);
        $course = self::getCourseDataByIdClass($mod->data->res[0]->id_class);

        return self::subjectsOfStudent($course['id_course'], $subject['id_student'], $subject);
        //return view ( 'admin', ['selectedMenu'=>'record','subject'=>$subject, 'course'=>$course, 'student'=>$student, 'msg'=>$msg]);
    }
    public static function recordPost(Request $req){
        $values = $req->only(['mark','type','id_subject']);

        switch ($values['type']){
            case 'exam':
                $mod = new Exams();
                break;
            case 'work':
            default: $mod = new Works();
        }
        $mod ->updateValueById('mark', $values['mark'], $values['id_subject']);
        if(!$mod->data->status){
            $msg = 'Error al actualizar la nota.';
        }else{
            $msg = 'Nota actualizada';
        }
        $mod->getById($values['id_subject']);
        $subject = self::getSubjectRequestedData($mod->data->res[0],$values['type']);
        $course = self::getCourseDataByIdClass($mod->data->res[0]->id_class);

        return self::subjectsOfStudent($course['id_course'], $subject['id_student'], $subject, $msg);
    }


    /*AUX FUNCTIONS*/
    private static function getCourseData($courseId, $role='admin'){
        $mod = new Courses();
        $mod->getById($courseId);
        $course =$mod->data->res[0];

        $course=['role'=>$role, 'id_course'=>$course->id_course, 'name'=>$course->name, 'date_start'=>$course->date_start, 'date_end'=>$course->date_end];
        return $course;
    }
    private static function getCourseDataByIdClass($id_class){
        $mod = new Classes();
        $mod -> getById($id_class);
        $id_course = $mod->data->res[0]->id_course;

        return self::getCourseData($id_course);
    }
    private static function getStudentData($id_student){
        $mod = new Students();
        $mod->getById($id_student);
        $student = $mod->data->res[0];

        $student = ['id_student'=>$student->id, 'name'=>$student->name, 'surname'=>$student->surname, 'email'=>$student->email, 'telephone'=>$student->telephone];
        return $student;
    }
    private static function getSubjectsArray($id_course, $id_student){
        $subjects = [];
        $mod = new JoinQueries();
        $mod->getAllExamsByCourseStudentExtended($id_course, $id_student);
        $exams = $mod->data->res;
        foreach($exams as $e){
            $subjects[] = $e;
        }
        $mod->getAllWorksByCourseStudentExtended($id_course, $id_student);
        $works = $mod->data->res;
        foreach ($works as $w){
            $subjects[] = $w;
        }
        $subjects = self::orderSubjectsByClass($subjects);
        $subjects = self::orderSubjectsByName($subjects);
        return $subjects;
    }

    private static function orderSubjectsByClass($data){
        for($i=0; $i < count($data); $i++){
            for($n=$i; $n < count($data);$n++){
                if($data[$i]->id_class > $data[$n]->id_class){
                    $value = $data[$i];
                    $data[$i] = $data[$n];
                    $data[$n] = $value;
                }
            }
        }
        return $data;
    }
    private static function orderSubjectsByName($data){
        for($i=0; $i < count($data); $i++){
            for($n=$i; $n < count($data);$n++){
                if(strcasecmp($data[$i]->subject_name,$data[$n]->subject_name)<0 && ($data[$i]->id_class == $data[$n]->id_class)){
                    $value = $data[$i];
                    $data[$i] = $data[$n];
                    $data[$n] = $value;
                }
            }
        }
        return $data;
    }
    private static function getSubjectRequestedData($subject, $type){
        switch($type){
            case 'exam':
                $id = $subject->id_exam;
                break;
            case 'work':
            default: $id = $subject->id_work;
        }
        $date = date_create($subject->deadline);
        $time = date_format($date,"h:m");
        $date = date_format($date,'Y-m-d');
        $subject = ['id_subject'=>$id, 'mark'=>$subject->mark, 'id_student'=>$subject->id_student, 'id_class'=>$subject->id_class, 'name' => $subject->name, 'date'=>$date, 'time'=>$time, 'description'=>$subject->description, 'type'=>$type];
        return $subject;
    }
}
