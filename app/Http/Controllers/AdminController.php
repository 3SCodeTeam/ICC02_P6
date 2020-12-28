<?php


namespace App\Http\Controllers;


use App\Models\Classes;
use App\Models\Courses;

use App\Models\JoinQueries;
use App\Models\Percentages;
use App\Models\Schedules;
use App\Models\Teachers;
use App\Models\UsersAdmin;

use App\Utils\DataValidator;
use App\Utils\MiscTools;
use DateInterval;
use DateTime;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use stdClass;


class AdminController extends Controller
{
    public static function start(Request $req){
        //Lo enviamos al perfil.
        return self::profile($req);
    }
    public static function profile(Request $req, $msg=null){
        $userId = $req->session()->get('sql_user_id');
        $mod = new UsersAdmin();
        $mod->getById($userId);
        $res = $mod->data->res;
        $user_data = self::getAdminUserData($res);
        return view('admin', ['user_data'=>$user_data, 'selectedMenu'=>'profile','msg'=>$msg]);
    }
    public static function profilePost(Request $req){
        $userId = $req->session()->get('sql_user_id');
        $option = $req->input('user_data_option');
        $value = $req->input('value');

        $mod = new UsersAdmin();
        $mod->getById($userId);
        $user_data = $mod->data->res[0];

        $checker = new DataValidator();
        $res = $checker->verifyData($value, $option, $user_data);
        if(!$res['status']){
            return self::profile($req, $msg=$res['msg']);
        }

        $res = self::updateDB($value,$option,$userId, $mod);

        if(!$res['status']){
            return self::profile($req, $res['msg']);
        }

        return self::profile($req, 'Datos actualizados');

    }
    public static function teacher(){
        $mod = new Teachers();
        $mod ->getAll();
        $teachers_data = $mod->data->res;

        return view('admin', ['selectedMenu'=>'teachers', 'teachers_data'=>$teachers_data]);
    }
    public static function teachersPost(Request $req){
        $mod = new Teachers();
        $mod->getAll();
        $data =['name'=>'teachers_data', 'values'=>$mod->data->res];
        $values = $req->only(['name','nif','surname','telephone','email','password']);

        //Validar datos del formulario.
        foreach ($values as $key=>$v){
            $checker = new DataValidator();
            switch ($key){
                case 'name':
                case 'surname':
                    if(!$checker->checkNames($v)){
                        return self::adminError('admin','El nombre contiene caracteres no validos.', 'teachers',$data);
                    }
                    break;
                case 'nif':
                    $res=$checker->checkNIF($v);
                    if(!$res['status']){return self::adminError('admin',$res['msg'],'teachers',$data);}
                    break;
                case 'password':
                    if(!$checker->checkLen($v,6)){return self::adminError('admin','La contraseña debe tener al menos 6 caracteres.','teachers',$data);}
                    break;
                case 'telephone':
                    if(!$checker->checkPhones($v)){return self::adminError('admin','Teléfono mal formado.','teachers',$data);}
                    break;
                case 'email':
                    if(!$checker->checkEmail($v)){return self::adminError('admin','Email mal formado.','teachers',$data);}
                    break;
            }
        }
        //Verificar datos UNIQUE
        foreach ($values as $key=>$v){
            switch ($key){
                case 'nif':
                    if(in_array($v,$data['values'])){return self::adminError('admin', 'El NIF introducido ya existe en la base de datos.','teachers',$data);}
                    break;
                case 'email':
                    if(in_array($v,$data['values'])){return self::adminError('admin', 'El email introducido ya existe en la base de datos.','teachers',$data);}
            }
        }
        //Insertar datos en DB
        $mod->insertValues($values['name'],$values['surname'],$values['telephone'],$values['nif'],$values['email'],Hash::make($values['password']));
        if($mod->data->affected_rows>0){
            $mod->getAll();
            $teachers_data = $mod->data->res;
            return view('admin',['msg'=>'¡Profesor registrado!', 'selectedMenu'=>'teachers','teachers_data'=>$teachers_data]);
        }
        return self::adminError('admin', 'Error de acceso a la base de datos.','teachers',$data);
    }
    public static function courses($msg=null){
        $mod = new Courses();
        $mod->getAll();
        $courses_data = $mod->data->res;

        return view('admin', ['courses_data'=>$courses_data, 'selectedMenu'=>'courses', 'msg'=>$msg]);
    }
    public static function coursesPost(Request $req){
        $values = $req->only(['name', 'date_start', 'date_end', 'active', 'description']);
        $mod = new Courses();
        $mod->getAll();
        $courses_data = $mod->data->res;

        if(MiscTools::in_ArrayObject($values['name'], $courses_data, 'name')){
           return self::courses('Este nombre del curso ya existe.');
        }

        if($values['date_start'] >= $values['date_end']){
            return self::courses('La fecha de inicio no puede ser posterior o igual a la de finalización.');
        }

        $mod->insertValues($values['name'],$values['description'],$values['date_start'],$values['date_end'],$values['active']);
        dd($mod);
        if($mod->data->status){
            return self::courses('Curso añadido.');
        }
        return self::courses('Error de acceso a la base de datos.');
    }
    public static function classes(string $msg=null){
        $tmod = new Teachers();
        $cmod = new Courses();
        $jmod = new JoinQueries();

        $tmod->getAll();
        $cmod->getByStatus(1);
        $jmod->getAllClassesData();

        $teachers = $tmod->data->res;
        $courses = $cmod->data->res;
        $classes = $jmod->data->res;

        return view('admin',['selectedMenu'=>'classes', 'teachers'=>$teachers, 'courses'=>$courses, 'classes'=>$classes, 'msg'=>$msg]);

    }
    public static function classesPost(Request $req, string $msg=null){
        $values = $req->only(['teacher','course']);
        $coursesMod = new Courses();
        $teachersMod = new Teachers();
        $coursesMod->getById($values['course']);
        $teachersMod->getById($values['teacher']);
        $course_data = $coursesMod->data->res;
        $teacher_data = $teachersMod->data->res;

        $mod = new JoinQueries();
        $mod->getUsedHoursByTeacherByCourseByDates($values['teacher'], $values['course']);
        $usedAvailableHours = $mod->data->res;

        $freeHoursOfWeek = self::availableHours($usedAvailableHours);

        $values['course_name'] = $course_data[0]->name;
        $values['teacher_email'] = $teacher_data[0]->email;

        return view('admin', ['selectedMenu'=>'classesSchedule', 'freeHoursOfWeek'=>$freeHoursOfWeek, 'formValues'=>$values, 'msg'=>$msg]);

    }
    public static function classesPostSchedule(Request $req){
        $inputSchedule = $req->except(['_token','color','name','course','teacher', 'workWeight']);
        $inputValues = $req->only(['color','name','course','teacher','workWeight']);

        $classMod = new Classes();
        $classMod -> getByIdCourse($inputValues['course']);

        If(MiscTools::in_ArrayObject($inputValues['name'],$classMod->data->res,'name')){
            return self::classesPost($req,'El nombre de la asignatura introducido ya existe en este curso.');
        }

        /*CREAR UNA NUEVA CLASE EN LA TABLA CLASS*/
        $classMod->insertValues($inputValues['teacher'],$inputValues['course'],0,$inputValues['name'],$inputValues['color']);
        if(!$classMod->data->status){
            return self::classesPost($req,'Error insertando los datos.');
        }
        /*OBTENER EL CLASS ID DE LA NUEVA CLASE CREADA*/
        $classMod->getByNameAndCourse($inputValues['name'], $inputValues['course']);
        $classData = $classMod->data->res[0];

        /*OBTENER LOS DATOS DEL CURSO DEL QUE DEPENDE LA CLASE*/
        $courseMod = new Courses();
        $courseMod -> getById($inputValues['course']);
        $courseData = $courseMod->data->res[0];

        /*CONVERTIR LAS FECHAS Y HORAS RECIBIDAS EN EL FORM EN UN ARRAY*/
        $values = self::stringToArray($inputSchedule, ';');

        /*GENERAR UN ARRAY CON LOS DATOS DE LAS HORAS Y DIAS DE LAS CLASES.*/
        $courseClasses = self::getArrayOfClasses($values, $courseData->date_start, $courseData->date_end, $classData->id_class);

        /*INSERTAR LOS DATOS DEL ARRAY CON LAS CLASES EN SCHEDULE*/
        $mod= new JoinQueries();
        if($mod->insertSchedule($courseClasses)){
            /*ACTUALIZAR EL VALOR id_schedule DE LA TABLA CLASS CON EL VALOR max(id_schedule) DE LA CLASE EN LA TABLA SCHEDULE*/
            $scheduleMod = new Schedules();
            $scheduleMod ->maxByIdClass($classData->id_class);
            $scheduleData = $scheduleMod->data->res[0];

            /*ACTUALIZAR EL PESO DE LA EVALUACIÓN CONTUNUA EN LA TABLA PERCENTAGE*/
            $workWeight = $inputValues['workWeight']/100;
            $examWeight = 1-$workWeight;

            $pMod = new Percentages();
            $pMod->insertValues($inputValues['course'], $classData->id_class, $workWeight, $examWeight);

            $classMod -> updateValueById('id_schedule',$scheduleData->id_schedule,$classData->id_class);
            return self::classes('Asignatura '.$classData->name.' creada.');
        }
        /*SI ALGO FALLA BORRAMOS LOS INSERT*/
        $classMod->deleteById($classData->id_class);
        return self::classesPost($req,'Error insertando los datos.');
    }
    public static function delete(){
        //TODO: logica para el borrado de los distintos elementos.
    }
    public static function courseActive($id_course){
        $mod = new Courses();
        $mod ->getById($id_course);

        $active = 1;
        if($mod->data->res[0]->active > 0){
            $active = 0;
        }

        $mod -> updateValueById('active', $active, $id_course);
        if(!$mod->data->status){
            return self::courses('No se ha podido actualizar el curso.');
        }
        return redirect()->route('admin',['courses']);
    }

    //AUX FUNCTIONS
    private static function getArrayOfClasses($values, $date_start, $date_end, $id_class): array
    {
        $start = new DateTime($date_start);
        $end = new DateTime($date_end);
        $daysInterval = ($start->diff($end))->days;
        $plus1Day = new DateInterval("P1D");
        $plus1Hour = new DateInterval("PT1H");

        $courseClasses=[];

        for($i = 0; $i < $daysInterval; $i++){
            foreach ($values as $dow=>$hours)
                if(self::dowCheck((intval($start->format('w')) + 1),$dow)){
                    foreach ($hours as $h){
                        $end_date = new DateTime('2000-01-01 '.$h);
                        $end_date->add($plus1Hour);
                        $courseClasses[]=['day'=>$start->format('Y-m-d'), 'time_start'=>$h, 'time_end'=>$end_date->format('H').':00', 'id_class'=>$id_class];
                    }
                }
            $start->add($plus1Day);
        }
        return $courseClasses;
    }
    private static function stringToArray($string, $separator): array
    {
        $values=[];
        foreach ($string as $k=>$v){
            $data=explode($separator,$k);
            $values[$data[0]][] = $data[1];
        }
        return $values;
    }
    private static function availableHours($usedHours): array
    {
        //MYSQL DOW => 1-7=>Sun-Sat
        $hours = ['08:00', '09:00', '10:00', '11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00'];
        $dow = ['LUNES', 'MARTES','MIÉRCOLES', 'JUEVES', 'VIERNES', 'SÁBADO', 'DOMINGO'];

        $avilableHours = [];
        foreach ($hours as $h){
            $freeHours=[];
            foreach ($dow as $d){
                $freeHour = true;
                foreach ($usedHours as $value){
                    if(self::dowCheck($value->DOW, $d) && substr($value->time_start,0,5) == $h){
                        $freeHour = false;
                        break;
                    }
                }
                $freeHours[$d]= $freeHour;
            }
            $avilableHours[$h] = $freeHours;
        }
        return $avilableHours;
    }
    private static function dowCheck($sql, $sp): bool
    {
        switch ($sql){
            case '1': return ($sp == 'DOMINGO');
            case '2': return ($sp == 'LUNES');
            case '3': return ($sp == 'MARTES');
            case '4': return ($sp == 'MIÉRCOLES');
            case '5': return ($sp == 'JUEVES');
            case '6': return ($sp == 'VIERNES');
            case '7': return ($sp == 'SÁBADO');
        }
        return false;
    }
    private static function getAdminUserData($res){
        $user_data = new stdClass();
        $user_data->name = $res[0]->name;
        $user_data->username = $res[0]->username;
        $user_data->email = $res[0]->email;
        return $user_data;
    }

    private static function updateDB($newValue, $attr, $userId, $mod)
    {
        $mod->updateValueById(self::checkOption($attr), $newValue, $userId);
        if(!$mod->data->status || !($mod->data->affected_rows > 0)){
            return ['msg'=>'Error al actulizar el valor en la base de datos.', 'status'=>false];
        }
        return ['msg'=>'Valor actualizado.', 'status'=>true];
    }
    private static function checkOption($option){
        switch($option){
        }
        //TODO: actualizar función update para hacerla común a varios models.
        return $option;
    }
    private static function adminError($view, $msg, $menu, $data){
        return view($view, ['selectedMenu'=>$menu, 'msg'=>$msg, $data['name']=>$data['values']]);
    }

}
