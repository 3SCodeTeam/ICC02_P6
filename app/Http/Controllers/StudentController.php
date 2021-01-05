<?php


namespace App\Http\Controllers;


use App\Models\Courses;
use App\Models\Enrollments;
use App\Models\Exams;
use App\Models\JoinQueries;
use App\Models\Notifications;
use App\Models\Percentages;
use App\Models\Students;
use App\Models\Works;
use App\Utils\MarkSTools;
use App\Utils\ScheduleTools;
use App\Utils\DataValidator;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;


class StudentController extends Controller
{
    /*MAIN FUNCTIONS*/
    public static function start(Request $req){/*PRIMERA CARGA DESDE EL LOGIN*/
        //Lo enviamos al perfil.
        return self::profile($req);
    }

    public static function profile(Request $req, $msg=null){/*SECCION PROFILE*/
        $id_student = $req->session()->get('sql_user_id');
        $existClasses = self::existClasses($id_student);
        $mod = new Students();
        $mod->getById($id_student);

        //Obtener los datos del usuario.
        $user_data = $mod->data->res[0];
        $user_data =DataValidator::safeUserData($user_data);

        //Obtener los datos de las notificaciones para el usuario
        $mod = new Notifications();
        $mod->getByIdStudent($id_student);
        $notifications = $mod->data->res[0];

        return view('student',['selectedMenu'=>'profile', 'user_data'=>$user_data, 'notifications'=>$notifications, 'msg'=>$msg, 'existClasses'=>$existClasses]);
    }
    public static function profilePost(Request $req){/*ACTUALIZACIÓN DATOS PROFILE*/
        $userId = $req->session()->get('sql_user_id');
        $option = $req->input('user_data_option');
        $value = $req->input('value');
        $notifications = $req->only('work', 'exam', 'continuous_assessment', 'final');
        $notifications = self::setNotificationsData($notifications);
        if($option === 'notifications'){
            //Actualizar notificaciones
            $nmod = new Notifications();
            $nmod -> updateAllValuesByIdStudent($notifications['work'], $notifications['exam'], $notifications['continuous_assessment'], $notifications['final'], $userId);
            return  self::profile($req, 'Notificaciones actualizadas');
        }

        $mod = new Students();
        $mod->getById(intval($userId));
        $user_data = $mod->data->res[0];

        $checker = new DataValidator();
        $res = $checker->verifyData($value,$option,$user_data);
        if(!$res['status']){
            return  self::profile($req, $res['msg']);
        }

        switch ($option){
            case 'username':
                $mod->getByUsername($value);
                if($mod->data->len > 0){
                    return self::profile($req, 'Este nombre de usuario ya está en uso.');
                }
                break;
            case 'email':
                $mod->getByEmail($value);
                if($mod->data->len > 0){
                    return self::profile($req, 'Este email ya está en uso.');
                }
                break;
            case 'nif':
                $mod->getByNif($value);
                if($mod->data->len > 0){
                    return self::profile($req, 'Este NIF ya ha sido registrado.');
                }
        }

        $res = self::updateDB($value,$option,$userId);

        if(!$res['status']){
            return  self::profile($req, $res['msg']);
        }

        //Actualizar notificaciones
        $nmod = new Notifications();
        $nmod -> updateAllValuesByIdStudent($notifications['work'], $notifications['exam'], $notifications['continuous_assessment'], $notifications['final'], $userId);

        return  self::profile($req, $res['msg']);

    }

    public static function enrollment(Request $req, $msg=null){/*SECCION ENROLLMENT*/
        $id_student = $req->session()->get('sql_user_id');
        $existClasses = self::existClasses($id_student);
        $mod = new Courses();
        $mod->getByStatus(1);
        $activeCourses = $mod->data->res;

        $studentCourses= self::coursesArrayByStatus($req->session()->get('sql_user_id'));
        return view('student',['selectedMenu'=>'enrollment', 'existClasses'=>$existClasses, 'msg'=>$msg, 'courses_data'=>$activeCourses, 'studentCourses'=>$studentCourses]);
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

            $course_mod->getByStatus('1');
            return self::enrollment($req, $msg);
        }

        //NUEVA MATRICULA
        $enroll_mod->getByStudentIdAndStatus($userId,'1');

        if($enroll_mod->data->len > 0){
            $enroll_mod->updateValueById('status', '0', $enroll_mod->data->res[0]->id_enrollment);
        }
        $enroll_mod->insertValues($userId, $id_course,'1');
        $msg = 'Matricula realizada.';
        $course_mod->getByStatus('1');

        /*
         * Verificar si existen trabajos y exámenes para ese curso.
         * Si exísten -> verificar si el estudiante está inscrito.
         *  Si: no hacer nada.
         *  No: actualizar trabajos y exámenes para el estudiante.
         * */
        self::newStudentCheckExams($id_course, $userId);
        self::newStudentCheckWorks($id_course, $userId);

        return self::enrollment($req, $msg);
    }

    public static function mSchedule(Request $req){/*SECCIÓN HOARIO PRIMERA CARGA DESDE EL MENÚ*/
        $id_student = $req->session()->get('sql_user_id');
        $current_date = $req->session()->get('schedule_date');
        $existClasses = self::existClasses($id_student);
        $schedule_data = ScheduleTools::buildMonthSchedule($id_student, $req);
        return view('student', ['selectedMenu'=>'mSchedule', 'existClasses'=>$existClasses, 'schedule_data' => $schedule_data, 'current_date'=>$current_date]);
    }
    public static function wSchedule(Request $req){/*SECCIÓN HORARIO SEMANAL CARGA DESDE EL MENÚ*/
        $id_student = $req->session()->get('sql_user_id');
        $current_date = $req->session()->get('schedule_date');
        $existClasses = self::existClasses($id_student);
        $schedule_data = ScheduleTools::buildWeekSchedule($id_student, $req);

        return view('student', ['selectedMenu'=>'wSchedule', 'existClasses'=>$existClasses, 'schedule_data'=>$schedule_data, 'current_date'=>$current_date]);
    }
    public static function dSchedule(Request $req){/*SECCIÓN HORARIO DIARIO CARGA DESDE EL MENÚ*/
        $id_student = $req->session()->get('sql_user_id');
        $existClasses = self::existClasses($id_student);
        $schedule_data = ScheduleTools::buildDaySchedule($id_student, $req);

        return view('student', ['selectedMenu'=>'dSchedule', 'existClasses'=>$existClasses, 'schedule_data'=>$schedule_data]);
    }
    public static function scheduleForward(Request $req, $type){
        $current_date = $req->session()->get('schedule_date');
        $date_interval = self::getInterval($type);

        $new_date = $current_date->add($date_interval);
        $req->session()->put(['schedule_date'=>$new_date]);

        return self::getSchedule($req, $type);
    }
    public static function scheduleBackward(Request $req, $type){
        $current_date = $req->session()->get('schedule_date');
        $date_interval = self::getInterval($type);

        $new_date = $current_date->sub($date_interval);
        $req->session()->put(['schedule_date'=>$new_date]);

        return self::getSchedule($req, $type);
    }
    public static function scheduleToday(Request $req, $type){
        $new_date = new DateTime(date('Y-m-d'));
        $req->session()->put(['schedule_date'=>$new_date]);

        return self::getSchedule($req, $type);
    }

    public static function record(Request $req){/*SECCIÓN RECORD CARGA DESDE EL MENÚ*/
        $id_student = $req->session()->get('sql_user_id');
        $existClasses = self::existClasses($id_student);
        $jMod = new JoinQueries();
        $classes = $jMod->getClassesByStudent($id_student);

        $cMod = new Courses();
        $cMod-> getById($classes->res[0]->id_course);
        $course = $cMod->data->res[0];

        $marks = MarkSTools::getClassesMarksByStudent($classes->res, $id_student);
        //$marks = self::getClassesMarks($classes->res, $userId);

        $courseMarks = MarkSTools::getCourseMarks($marks);

        return view ('student', ['selectedMenu'=>'record', 'existClasses'=>$existClasses, 'classes'=>$classes->res, 'course' =>$course, 'marks'=>$marks, 'courseMarks' =>$courseMarks]);
    }
    public static function recordDetail($id_class, Request $req){/*SECCIÓN DETALLES ASIGNATURA*/
        $id_student = $req->session()->get('sql_user_id');
        $existClasses = self::existClasses($id_student);
        $wMod = new Works();
        $eMod = new Exams();
        $jMod = new JoinQueries();

        $wMod->getByIdClassAndIdStudent($id_class, $id_student);
        $works = $wMod->data->res;

        $eMod->getByIdClassAndIdStudent($id_class, $id_student);
        $exams = $eMod->data->res;

        $jMod -> getAllClassDatabyId($id_class);
        $data = $jMod->data->res[0];

        return view('student', ['selectedMenu'=>'recordDetails', 'existClasses'=>$existClasses, 'exams'=>$exams, 'works'=>$works, 'data'=>$data]);
    }

    /*AUX FUNCTIONS*/
    private static function getSchedule($req, $type){
        switch ($type){
            case 'dSchedule': return self::dSchedule($req);
            case 'wSchedule': return self::wSchedule($req);
            case 'mSchedule':
            default: return self::mSchedule($req);
        }
    }
    private static function getInterval($type): DateInterval
    {
        switch ($type){
            case 'dSchedule': $date_interval = new DateInterval('P1D');break;
            case 'wSchedule': return $date_interval = new DateInterval('P7D'); break;
            case 'mSchedule':
            default: $date_interval = new DateInterval('P1M');
        }
        return $date_interval;
    }
    private static function getClassesMarksWeights($id_class){
        $pMod = new Percentages();
        $pMod->getByIdClass($id_class);
        $eWeight = $pMod->data->res[0]->exams;
        $wWeight = $pMod->data->res[0]->continuous_assessment;

        return ['exam'=>$eWeight, 'works'=>$wWeight];
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
        return ['msg'=>'Valor actualizado.', 'status'=>true];
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
                    $subject[] = ['id_class'=>$r->id_class, 'id_student'=>$userId, 'name'=>$r->name, 'deadline'=>$r->deadline, 'description'=>$r->description, 'mark'=>-1];
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
                    $subject[] = ['id_class'=>$r->id_class, 'id_student'=>$userId, 'name'=>$r->name, 'deadline'=>$r->deadline, 'description'=>$r->description, 'mark'=>-1];
                }
                $joinQ_mod->insertMultiple($subject, 'works');

            }
        }
    }
    private static function setNotificationsData(array $selectedNotifications): array
    {
        $notifications = ['work'=>0, 'exam'=>0, 'continuous_assessment'=>0, 'final'=>0];
        foreach ($selectedNotifications as $k=>$v){
            $notifications[$k] = $v;
        }
        return $notifications;
    }
    private static function existClasses($id_student): bool
    {
        $jMod = new JoinQueries();
        $classes = $jMod->getClassesByStudent($id_student);
        if($classes->len>0){
            return true;
        }
        return false;
    }
}
