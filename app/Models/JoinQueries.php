<?php


namespace App\Models;


use Illuminate\Support\Facades\DB;
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
            $res = DB::connection('mysql')->select($stm, $values);
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
        $stm='SELECT DISTINCT DAYOFWEEK(S.day) as DOW, S.time_start, S.time_end, C.name as class_name, C.color, Co.name as course_name, T.email, T.name as Tname, T.surname
        FROM class as C
        INNER JOIN courses as Co ON C.id_course = Co.id_course
        INNER JOIN teachers AS T ON C.id_teacher = T.id_teacher
        INNER JOIN schedule AS S ON C.id_schedule = S.id_schedule
        ORDER BY DOW';
        try{
            $res = DB::connection('mysql')->select($stm);
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

    /*Query for dayClasses
        SELECT DISTINCT DAYOFWEEK(S.day), S.time_start, S.time_end
        FROM schedule as S INNER JOIN class as C on S.id_class = C.id_class
        WHERE C.id_teacher = 1 and S.day BETWEEN '2020-01-01' and '2020-12-31'
    */

    public function getScheduleByTeacherAndDate($id_teacher, $start_date, $end_date){
        $values = [$id_teacher, $start_date, $end_date];
        $stm='SELECT DISTINCT DAYOFWEEK(S.day) as DOW, S.time_start, S.time_end, C.name, C.color, Co.name as course_name, T.email, T.name as Tname, T.surname
        FROM class as C
        INNER JOIN courses as Co ON C.id_course = Co.id_course
        INNER JOIN teachers AS T ON C.id_teacher = T.id_teacher
        INNER JOIN schedule AS S ON C.id_schedule = S.id_schedule
        WHERE C.id_teacher = ? and S.day BETWEEN ? and ?';
        try{
            $res = DB::connection('mysql')->select($stm,$values);
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
