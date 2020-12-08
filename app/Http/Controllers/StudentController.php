<?php


namespace App\Http\Controllers;


use App\Models\Courses;
use App\Models\Enrollments;
use App\Models\Students;
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
            return view('student',['selectedMenu'=>'profile','user_data'=>$user_data, 'msg'=>$res['msg']]);
        }

        $mod->getById($userId);
        return view('student',['selectedMenu'=>'profile', 'user_data'=>$mod->data->res[0], 'msg'=>$res['msg']]);
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
            $value = 1;
            $course_mod->getById($id_course);
            $msg = $course_mod->data->res[0]->name.' activado';
            if($enroll_mod->data->res[0]->status > 0){
                $value = 0;
                $msg = $course_mod->data->res[0]->name.' desactivado';
            }
            $enroll_mod->updateValueById('status', $value, $enroll_mod->data->res[0]->id_enrollment);

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
    public static function record(){/*SECCIÓN RECORD CARGA DESDE EL MENÚ*/}
    public static function recordDetail($id_class){/*SECCIÓN DETALLES ASIGNATURA*/}

    /*AUX FUNCTIONS*/
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
}
