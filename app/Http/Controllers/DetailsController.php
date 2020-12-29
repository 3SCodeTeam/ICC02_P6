<?php


namespace App\Http\Controllers;


use App\Models\Classes;
use App\Models\Courses;
use App\Models\Exams;
use App\Models\JoinQueries;
use App\Models\Percentages;
use App\Models\Works;
use App\Utils\MiscTools;
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
    public static function classesDetails($id_course,$id_class=null, $type=null, $msg=null){
        $joinMod = new JoinQueries();
        $classes = $joinMod->getClassesAndTeachersByCourse($id_course);
        $course= MiscTools::getCourseData($id_course);
        $teachers = [];
        if(isset($type) && $type==='teacher'){
            $teachers = MiscTools::getAvailableTeachers($id_class);
        }
        $percent=['id_class'=>$id_class, 'type'=>$type];

        return view('admin', ['selectedMenu'=>'classesDetails', 'teachers'=>$teachers, 'course'=>$course, 'classes'=>$classes->res, 'percent'=>$percent, 'msg'=>$msg]);
    }
    public static function percentPost(Request $req){
        $post = $req->except('_token');
        $course = MiscTools::getCourseDataByIdClass($post['id_class']);

        switch ($post['type']){
            case 'name':
                $mod = new Classes();
                $mod -> getByIdCourse($course['id_course']);
                If(MiscTools::in_ArrayObject($post['name'],$mod->data->res,'name')){
                    return self::classesDetails($course['id_course'],null,null,'El nombre introducido ya existe en este curso.');
                }
                $mod -> updateValueById('name', $post['name'], $post['id_class']);
                break;
            case 'continuous_assessment':
            case 'exams':
                $mod = new Percentages();
                $data = self::getPercents($post[$post['type']], $post['type']);
                if($mod ->updateMultipleValuesById($data,['id_class'=>$post['id_class']])<1){
                    return self::classesDetails($course['id_course'],null, null, 'No se ha actualizado el dato.');
                };
                break;
        }
        return self::classesDetails($course['id_course'],null, null, 'Dato actualizado.');
    }
    public static function teacherUpdate(Request $req){
        $post = $req->except(['_token']);

        $course = MiscTools::getCourseDataByIdClass($post['id_class']);
        $mod = new Classes();
        $mod -> updateValueById('id_teacher', $post['new_teacher'], $post['id_class']);
        if(!$mod->data->status){
            return self::classesDetails($course['id_course'], $post['id_class'], 'teacher','No se ha podido actualizar el dato.');
        }
        return self::classesDetails($course['id_course'],null,null, 'Dato actualizado');
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
        $course = MiscTools::getCourseData($courseId, $role);

        return view('details', ['role'=>$role, 'classes' => $mod->data->len, 'selectedMenu'=>'subjectsDetails', 'course'=>$course, 'exams'=>$exams, 'works'=>$works, 'percentage'=>$percentages]);
    }
    public static function subjectsOfStudent($id_course, $id_student, $subject=null, $msg=null){
        $course = MiscTools::getCourseData($id_course);
        $student = MiscTools::getStudentData($id_student);

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
        $course = MiscTools::getCourseDataByIdClass($mod->data->res[0]->id_class);

        return self::subjectsOfStudent($course['id_course'], $subject['id_student'], $subject);
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
        $course = MiscTools::getCourseDataByIdClass($mod->data->res[0]->id_class);

        return self::subjectsOfStudent($course['id_course'], $subject['id_student'], $subject, $msg);
    }


    /*AUX FUNCTIONS*/
    private static function getPercents($value, $type='continuous_assessment'): array
    {
        $data = ['exams'=>0, 'continuous_assessment'=>0];
        foreach ($data as $k => $v){
            if($k === $type){
                $data[$k] = $value / 100;
            }else{
                $data[$k] = 1 - ($value/100);
            }
        }
        return $data;
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
