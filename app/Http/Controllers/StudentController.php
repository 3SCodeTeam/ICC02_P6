<?php


namespace App\Http\Controllers;


use App\Models\Courses;
use App\Models\Enrollments;
use App\Models\Exams;
use App\Models\JoinQueries;
use App\Models\Percentages;
use App\Models\Students;
use App\Models\Works;
use App\Utils\MarkSTools;
use App\Utils\ScheduleTools;
use App\Utils\DataValidator;
use Illuminate\Http\Request;



class StudentController extends Controller
{
    /*MAIN FUNCTIONS*/
    public static function start(Request $req){/*PRIMERA CARGA DESDE EL LOGIN*/
        //Lo enviamos al perfil.
        return self::profile($req);
    }
    public static function profile(Request $req){/*SECCION PROFILE*/
        $mod = new Students();
        $mod->getById($req->session()->get('sql_user_id'));
        $user_data = $mod->data->res[0];
        $user_data =DataValidator::safeUserData($user_data);
        return view('student',['selectedMenu'=>'profile', 'user_data'=>$user_data]);
    }
    public static function profilePost(Request $req){/*ACTUALIZACIÓN DATOS PROFILE*/
        $userId = $req->session()->get('sql_user_id');
        $option = $req->input('user_data_option');
        $value = $req->input('value');

        $mod = new Students();
        $mod->getById(intval($userId));
        $user_data = $mod->data->res[0];

        $checker = new DataValidator();
        $res = $checker->verifyData($value,$option,$user_data);
        if(!$res['status']){
            return view('student',['selectedMenu'=>'profile','user_data'=>$user_data, 'msg'=>$res['msg']]);
        }
        //TODO: db data check for username, email, nif,
        $res = self::updateDB($value,$option,$userId);
        if(!$res['status']){
            $user_data =DataValidator::safeUserData($user_data);
            return view('student',['selectedMenu'=>'profile','user_data'=>$user_data, 'msg'=>$res['msg']]);
        }

        $mod->getById($userId);
        $user_data =DataValidator::safeUserData($mod->data->res[0]);
        return view('student',['selectedMenu'=>'profile', 'user_data'=>$user_data, 'msg'=>$res['msg']]);
    }
    public static function enrollment(Request $req){/*SECCION ENROLLMENT*/
        $mod = new Courses();
        $mod->getByStatus(1);
        $activeCourses = $mod->data->res;

        $studentCourses= self::coursesArrayByStatus($req->session()->get('sql_user_id'));
        return view('student',['selectedMenu'=>'enrollment', 'courses_data'=>$activeCourses, 'studentCourses'=>$studentCourses]);
    }
    public static function enrollmentPost(Request $req){/*DATOS DEL FORMULARIO DE MATRICULACIÓN*/
        $id_course = $req->get('courses');
        $userId = $req->session()->get('sql_user_id');
        $course_mod = new Courses();
        $enroll_mod = new Enrollments();

        /*Comprobar si ya está matriculado de este curso.
            SI: determinar estado del curso y cambiarlo al contrio.
            No: Comprobar si está matriculado de otro curso activo.
                SI: desactivar el curso antiguo y matricular y activar el nuevo.
                NO: matricular el nuevo y activar.

        Devolver los cursos activos, los cursos del estudiante, mensaje respuesta a la acción.
        */
        //ACTIVAR-DESACTIVAR MATRICULA CURSO
        $enroll_mod ->getByCourseIdAndStudentId($id_course, $userId);

        if($enroll_mod->data->len > 0){
            $course_mod->getById($id_course);
            $id_enrollment = $enroll_mod->data->res[0]->id_enrollment;
            if($enroll_mod->data->res[0]->status > 0){
                $value = 0;
                $msg = $course_mod->data->res[0]->name.' desactivado';
            }else{
                $enroll_mod->updateValue('status', '0', 'id_student', $userId);
                $value = 1;
                $msg = $course_mod->data->res[0]->name.' activado';
            }
            $enroll_mod->updateValueById('status', $value, $id_enrollment);

            $studentCourses = self::coursesArrayByStatus($userId);
            $course_mod->getByStatus('1');
            return view('student',['selectedMenu'=>'enrollment', 'courses_data'=>$course_mod->data->res, 'studentCourses'=>$studentCourses, 'msg'=>$msg]);
        }

        //NUEVA MATRICULA
        $enroll_mod->getByStudentIdAndStatus($userId,'1');

        if($enroll_mod->data->len > 0){
            $enroll_mod->updateValueById('status', '0', $enroll_mod->data->res[0]->id_enrollment);
        }
        $enroll_mod->insertValues($userId, $id_course,'1');
        $msg = 'Matricula realizada.';
        $course_mod->getByStatus('1');
        $studentCourses = self::coursesArrayByStatus($userId);

        /*
         * Verificar si existen trabajos y exámenes para ese curso.
         * Si exísten -> verificar si el estudiante está inscrito.
         *  Si: no hacer nada.
         *  No: actualizar trabajos y exámenes para el estudiante.
         * */
        self::newStudentCheckExams($id_course, $userId);
        self::newStudentCheckWorks($id_course, $userId);

        return view('student',['selectedMenu'=>'enrollment', 'courses_data'=>$course_mod->data->res, 'studentCourses'=>$studentCourses, 'msg'=>$msg]);
    }
    public static function mSchedule(Request $req){/*SECCIÓN HOARIO PRIMERA CARGA DESDE EL MENÚ*/
        $userId = $req->session()->get('sql_user_id');
        $schedule_data = ScheduleTools::buildMonthSchedule($userId);
        return view('student', ['selectedMenu'=>'mSchedule', 'schedule_data' => $schedule_data]);
    }
    public static function wSchedule(Request $req){/*SECCIÓN HORARIO SEMANAL CARGA DESDE EL MENÚ*/
        $userId = $req->session()->get('sql_user_id');
        $schedule_data = ScheduleTools::buildWeekSchedule($userId);

        return view('student', ['selectedMenu'=>'wSchedule', 'schedule_data'=>$schedule_data]);
    }
    public static function dSchedule(Request $req){/*SECCIÓN HORARIO DIARIO CARGA DESDE EL MENÚ*/
        $userId = $req->session()->get('sql_user_id');
        $schedule_data = ScheduleTools::buildDaySchedule($userId);

        return view('student', ['selectedMenu'=>'dSchedule', 'schedule_data'=>$schedule_data]);
    }
    public static function record(Request $req){/*SECCIÓN RECORD CARGA DESDE EL MENÚ*/
        $userId = $req->session()->get('sql_user_id');
        $jMod = new JoinQueries();
        $classes = $jMod->getClassesByStudent($userId);

        $cMod = new Courses();
        $cMod-> getById($classes->res[0]->id_course);
        $course = $cMod->data->res[0];

        $marks = MarkSTools::getClassesMarksByStudent($classes->res, $userId);
        //$marks = self::getClassesMarks($classes->res, $userId);

        return view ('student', ['selectedMenu'=>'record', 'classes'=>$classes->res, 'course' =>$course, 'marks'=>$marks]);
    }
    public static function recordDetail($id_class, Request $req){/*SECCIÓN DETALLES ASIGNATURA*/
        $userId = $req->session()->get('sql_user_id');
        $wMod = new Works();
        $eMod = new Exams();
        $jMod = new JoinQueries();

        $wMod->getByIdClassAndIdStudent($id_class, $userId);
        $works = $wMod->data->res;

        $eMod->getByIdClassAndIdStudent($id_class, $userId);
        $exams = $eMod->data->res;

        $jMod -> getAllClassDatabyId($id_class);
        $data = $jMod->data->res[0];

        return view('student', ['selectedMenu'=>'recordDetails', 'exams'=>$exams, 'works'=>$works, 'data'=>$data]);
    }

    /*AUX FUNCTIONS*/
    private static function getClassesMarksWeights($id_class){
        $pMod = new Percentages();
        $pMod->getByIdClass($id_class);
        $eWeight = $pMod->data->res[0]->exams;
        $wWeight = $pMod->data->res[0]->continuous_assessment;

        return ['exam'=>$eWeight, 'works'=>$wWeight];
    }
    private static function getClassesMarks($classes, $userId){
        $wMod = new Works();
        $eMod = new Exams();
        $marks = [];

        foreach ($classes as $c){
            $wMod->getByIdClassAndIdStudent($c->id_class, $userId);
            $worksMarks = '----';
            foreach ($wMod->data->res as $w){
                if(!(isset($w->mark))){
                    $worksMarks = '----';
                    break;
                }
                $worksMarks += $w->mark;
            }
            if(!$worksMarks == '----'){
                $eMod->getByIdClassAndIdStudent($c->id_class, $userId);
                $examsMarks = '----';
                foreach ($eMod->data->res as $e){
                    if(!(isset($e->mark))){
                        $examsMarks = '----';
                        break;
                    }
                    $examsMarks += $e->mark;
                }
            }else{
                $examsMarks = '----';
            }
            $weights = self::getClassesMarksWeights($c->id_class);
            if($examsMarks === '----' || $worksMarks === '----'){
                $marks[$c->id_class] = ['exam'=>$examsMarks, 'work'=>$worksMarks, 'global'=>'----', 'weights'=>$weights];
            }else{
                $global = $examsMarks*$weights['exams'] + $worksMarks*$weights['works'];
                $marks[$c->id_class] = ['exam'=>$examsMarks, 'work'=>$worksMarks, 'global'=>$global, 'weights'=>$weights];
            }
        }
        return $marks;
    }
    private static function checkOption($option){
        switch($option){
            case 'Password':
            case 'password': return 'pass';
        }
        return $option;
    }
    private static function updateDB($newValue, $attr, $userId)
    {
        $mod = new Students();
        $mod->updateValueById(self::checkOption($attr), $newValue, $userId);
        if(!$mod->data->status || !($mod->data->affected_rows > 0)){
            return ['msg'=>'Error al actulizar el valor en la base de datos.', 'status'=>false];
        }
        return ['msg'=>'¡Valor actualizado!', 'status'=>true];
    }

    private static function coursesArrayByStatus($Id){
        $mod = new Enrollments();
        $mod->getByIdStudent($Id);
        $courses = $mod->data->res;
        $active = [];
        $inactive = [];
        foreach ($courses as $c){
            if($c->status){
                $inactive[]=$c->id_course;
            }else{
                $active[]=$c->id_course;
            }
        }
        return ['active'=>$active, 'inactive'=>$inactive];
    }

    private static function newStudentCheckExams($id_course, $userId){
        $joinQ_mod = new JoinQueries();
        $exams = $joinQ_mod -> getAllExamsByCourse($id_course);
        if($exams->len > 0){
            $studentExams = $joinQ_mod->getAllExamsByCourseStudent($id_course, $userId);
            if($studentExams->len < 1){
                $subject=[];
                foreach ($exams->res as $r){
                    $subject = ['id_class'=>$r->id_class, 'id_student'=>$userId, 'name'=>$r->name, 'deadline'=>$r->deadline, 'description'=>$r->description, 'mark'=>-1];
                }
                $joinQ_mod->insertMultiple($subject, 'exams');
            }
        }
    }
    private static function newStudentCheckWorks($id_course, $userId){
        $joinQ_mod = new JoinQueries();
        $works = $joinQ_mod -> getAllWorksByCourse($id_course);
        if($works->len > 0){
            $studentWorks = $joinQ_mod ->getAllWorksByCourseStudent($id_course, $userId);
            if($studentWorks->len < 1){
                $subject=[];
                foreach ($works->res as $r){
                    $subject = ['id_class'=>$r->id_class, 'id_student'=>$userId, 'name'=>$r->name, 'deadline'=>$r->deadline, 'description'=>$r->description, 'mark'=>-1];
                }
                $joinQ_mod->insertMultiple($subject, 'works');
            }
        }
    }
}
