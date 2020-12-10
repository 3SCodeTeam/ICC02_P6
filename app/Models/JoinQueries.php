<?php


namespace App\Models;


use Illuminate\Support\Facades\DB as DBAlias;
use App\Entities\Data;

class JoinQueries
{
    public Data $data;

    public function __construct(){
        $this->data = new Data();
    }

    public function getClassesOfDay($id, $date){
        $values = [$id, $date];
        $stm = 'SELECT S.id_class, S.day, S.time_start, S.time_end, C.name as class_name, C.color, Co.name as course_name FROM schedule as S inner JOIN class as C ON S.id_class=C.id_class INNER JOIN courses as Co ON C.id_course = Co.id_course
        WHERE Co.id_course IN (SELECT id_course FROM enrollment WHERE id_student = ?) and S.day =? ORDER BY S.day, S.time_start';
        try{
            $res = DBAlias::connection('mysql')->select($stm, $values);
            $this->data->len = count($res);
            $this->data->res = $res;
            $this->data->status = true;
        }catch(Exception $e){
            $this->data->err = $e->getMessage();
            $this->data->status = false;
            dd($e->getMessage());
        } finally {
            return $this->data;
        }
    }

    public function getByDOW(){
        $stm='SELECT DAYOFWEEK(S.day) as DOW, S.time_start, S.time_end, C.name as class_name, C.color, Co.name as course_name, T.email, T.name as Tname, T.surname
        FROM class as C
        INNER JOIN courses as Co ON C.id_course = Co.id_course
        INNER JOIN teachers AS T ON C.id_teacher = T.id_teacher
        INNER JOIN schedule AS S ON C.id_schedule = S.id_schedule
        ORDER BY DOW';
        try{
            $res = DBAlias::connection('mysql')->select($stm);
            $this->data->len = count($res);
            $this->data->res = $res;
            $this->data->status = true;
        }catch(Exception $e){
            $this->data->err = $e;
            $this->data->status = false;
            dd($e->getMessage());
        } finally {
            return $this->data;
        }
    }

    //Devuelve las horas ocupadas de un profesor en un periodo por días de la semana.
    public function getScheduleByTeacherAndDate($id_teacher, $start_date, $end_date){
        $values = [$id_teacher, $start_date, $end_date];
        $stm='SELECT DAYOFWEEK(S.day) as DOW, S.time_start, S.time_end, C.name as class_name, C.color, Co.name as course_name, T.email, T.name as teacher_name, T.surname
        FROM class as C
        INNER JOIN courses as Co ON C.id_course = Co.id_course
        INNER JOIN teachers AS T ON C.id_teacher = T.id_teacher
        INNER JOIN schedule AS S ON C.id_schedule = S.id_schedule
        WHERE C.id_teacher = ? and S.day BETWEEN ? and ?';
        try{
            $res = DBAlias::connection('mysql')->select($stm,$values);
            $this->data->len = count($res);
            $this->data->res = $res;
            $this->data->status = true;
        }catch(Exception $e){
            $this->data->err = $e;
            $this->data->status = false;
            dd($e->getMessage());
        } finally {
            return $this->data;
        }
    }
    //Devuelve las horas ocupadas de un profesor y/o curso en un peridodo.
    public function getUsedHoursByTeacherByCourseByDates($id_teacher, $id_course){
        $values = [$id_teacher, $id_course, $id_course, $id_course];
        $stm='SELECT distinct DAYOFWEEK(S.day) as DOW, S.time_start, S.time_end, C.name as class_name, C.color, Co.name as course_name, Co.active, T.email, T.name as teacher_name, T.surname
        FROM class as C
        INNER JOIN courses as Co ON C.id_course = Co.id_course
        INNER JOIN teachers AS T ON C.id_teacher = T.id_teacher
        INNER JOIN schedule AS S ON C.id_class = S.id_class
        WHERE (C.id_teacher = ? or Co.id_course = ?) and S.day BETWEEN
        (SELECT date_start FROM courses where id_course = ?) and (SELECT date_end FROM courses where id_course = ?)
		ORDER BY DOW, S.time_start';
        try{
            $res = DBAlias::connection('mysql')->select($stm,$values);
            $this->data->len = count($res);
            $this->data->res = $res;
            $this->data->status = true;
        }catch(Exception $e){
            $this->data->err = $e;
            $this->data->status = false;
            dd($e->getMessage());
        } finally {
            return $this->data;
        }
    }
    //DEVUELVE TODAS LAS CLASES + DATOS CURSO + DATOS PROFESOR ORDENADO POR CURSO Y PRFESOR
    public function getAllClassesData(){
        $stm = 'SELECT c.id_class as id_class, c.id_teacher as id_teacher, c.id_course as id_course, C.name as class_name, C.color, Co.name as course_name, Co.active, T.email, T.name as teacher_name, T.surname
        FROM class as C INNER JOIN courses as Co ON C.id_course = Co.id_course INNER JOIN teachers AS T ON C.id_teacher = T.id_teacher
        ORDER BY id_course, id_teacher';
        try{
            $res = DBAlias::connection('mysql')->select($stm);
            $this->data->len = count($res);
            $this->data->res = $res;
            $this->data->status = true;
        }catch(Exception $e){
            $this->data->err = $e;
            $this->data->status = false;
            dd($e->getMessage());
        } finally {
            return $this->data;
        }

    }
    //Insert para multiples sentencias SCHEDULE TABLE
    public function insertSchedule(array $data){
        return DBAlias::table('schedule')->insert($data);
    }

    //Detalles de estudiantes de un curso
    public function getStudentsByCourse($id_course){
        $values=[$id_course];
        $stm= 'SELECT id, name, surname, username, email, nif, telephone, status, id_course, id_enrollment from students as S inner join enrollment as E on S.id = E.id_student where id_course = ?';
        try{
            $res = DBAlias::connection('mysql')->select($stm, $values);
            $this->data->len = count($res);
            $this->data->res = $res;
            $this->data->status = true;
        }catch(Exception $e){
            $this->data->err = $e;
            $this->data->status = false;
            dd($e->getMessage());
        } finally {
            return $this->data;
        }
    }

    //Detalles de classes (datos profesor) de un curso
    public function getClassesAndTeachersByCourse($id_course){
       $values=[$id_course];
       $stm = 'SELECT C.id_class, C.id_course, C.id_teacher, C.name as class_name, C.color, Co.name as course_name, Co.description, Co.date_start, Co.date_end, Co.active, T.email, T.name as teacher_name, T.nif, T.surname, T.telephone FROM class as C INNER JOIN courses as Co ON C.id_course = Co.id_course INNER JOIN teachers AS T ON C.id_teacher = T.id_teacher WHERE C.id_course = ?';
        try{
            $res = DBAlias::connection('mysql')->select($stm, $values);
            $this->data->len = count($res);
            $this->data->res = $res;
            $this->data->status = true;
        }catch(Exception $e){
            $this->data->err = $e;
            $this->data->status = false;
            dd($e->getMessage());
        } finally {
            return $this->data;
        }
    }
}
