<?php


namespace App\Models;



use Exception;
use Illuminate\Support\Facades\DB as DBAlias;
use App\Entities\Data;

class JoinQueries
{
    public Data $data;

    public function __construct(){
        $this->data = new Data();
    }

    public function getClassesOfDay($id, $date): Data
    {
        $values = [$id, $date];
        $stm = 'SELECT S.id_class, S.day, S.time_start, S.time_end, C.name as class_name, C.color, Co.name as course_name FROM schedule as S inner JOIN class as C ON S.id_class=C.id_class INNER JOIN courses as Co ON C.id_course = Co.id_course
        WHERE Co.id_course IN (SELECT id_course FROM enrollment WHERE id_student = ? and status = 1) and S.day =? ORDER BY S.day, S.time_start';
        $this->data = self::doQuery($stm, $values);
        return $this->data;
    }

    public function getByDOW(): Data
    {
        $stm='SELECT DAYOFWEEK(S.day) as DOW, S.time_start, S.time_end, C.name as class_name, C.color, Co.name as course_name, T.email, T.name as Tname, T.surname
        FROM class as C
        INNER JOIN courses as Co ON C.id_course = Co.id_course
        INNER JOIN teachers AS T ON C.id_teacher = T.id_teacher
        INNER JOIN schedule AS S ON C.id_schedule = S.id_schedule
        ORDER BY DOW';
        $this->data = self::doQuery($stm);
        return $this->data;
    }

    //Devuelve las horas ocupadas de un profesor en un periodo por días de la semana.
    public function getScheduleByTeacherAndDate($id_teacher, $start_date, $end_date): Data
    {
        $values = [$id_teacher, $start_date, $end_date];
        $stm='SELECT DAYOFWEEK(S.day) as DOW, S.time_start, S.time_end, C.name as class_name, C.color, Co.name as course_name, T.email, T.name as teacher_name, T.surname
        FROM class as C
        INNER JOIN courses as Co ON C.id_course = Co.id_course
        INNER JOIN teachers AS T ON C.id_teacher = T.id_teacher
        INNER JOIN schedule AS S ON C.id_schedule = S.id_schedule
        WHERE C.id_teacher = ? and S.day BETWEEN ? and ?';
        $this->data = self::doQuery($stm, $values);
        return $this->data;
    }
    //Devuelve las horas ocupadas de un profesor y/o curso en un peridodo.
    public function getUsedHoursByTeacherByCourseByDates($id_teacher, $id_course): Data
    {
        $values = [$id_teacher, $id_course, $id_course, $id_course];
        $stm='SELECT distinct DAYOFWEEK(S.day) as DOW, S.time_start, S.time_end, C.name as class_name, C.color, Co.name as course_name, Co.active, T.email, T.name as teacher_name, T.surname
        FROM class as C
        INNER JOIN courses as Co ON C.id_course = Co.id_course
        INNER JOIN teachers AS T ON C.id_teacher = T.id_teacher
        INNER JOIN schedule AS S ON C.id_class = S.id_class
        WHERE (C.id_teacher = ? or Co.id_course = ?) and S.day BETWEEN
        (SELECT date_start FROM courses where id_course = ?) and (SELECT date_end FROM courses where id_course = ?)
		ORDER BY DOW, S.time_start';
        $this->data = self::doQuery($stm, $values);
        return $this->data;
    }
    //DEVUELVE TODAS LAS CLASES + DATOS CURSO + DATOS PROFESOR ORDENADO POR CURSO Y PRFESOR
    public function getAllClassesData(): Data
    {
        $stm = 'SELECT c.id_class, c.id_teacher, c.id_course, C.name as class_name, C.color,
                Co.name as course_name, Co.active, Co.description, CO.date_start, CO.date_end,
                T.email, T.name as teacher_name, T.surname,
                P.continuous_assessment as works, P.exams
                FROM class as C
                INNER JOIN courses as Co ON C.id_course = Co.id_course
                INNER JOIN teachers AS T ON C.id_teacher = T.id_teacher
                INNER JOIN percentage AS P ON C.id_class = P.id_class
                ORDER BY id_course, id_teacher';
        $this->data = self::doQuery($stm);
        return $this->data;
    }
    //Insert para multiples sentencias SCHEDULE TABLE
    public function insertSchedule(array $data): bool
    {
        return DBAlias::table('schedule')->insert($data);
    }

    //Detalles de estudiantes de un curso
    public function getStudentsDataByCourse($id_course): Data
    {
        $values=[$id_course];
        $stm= 'SELECT id, name, surname, username, email, nif, telephone, status, id_course, id_enrollment from students as S inner join enrollment as E on S.id = E.id_student where id_course = ?';
        $this->data = self::doQuery($stm, $values);
        return $this->data;
    }

    //Detalles de classes (datos profesor) de un curso
    public function getClassesAndTeachersByCourse($id_course): Data
    {
       $values=[$id_course];
       $stm = 'SELECT C.id_class, C.id_course, C.id_teacher, C.name as class_name, C.color, Co.name as course_name, Co.description, Co.date_start, Co.date_end,
                Co.active, T.email, T.name as teacher_name, T.nif, T.surname, T.telephone, P.exams, P.continuous_assessment
                FROM class as C
                INNER JOIN courses as Co ON C.id_course = Co.id_course
                INNER JOIN teachers AS T ON C.id_teacher = T.id_teacher
                INNER JOIN percentage AS P ON P.id_class = C.id_class
                WHERE C.id_course = ?';
        $this->data = self::doQuery($stm, $values);
        return $this->data;
    }
    public function getClassesCoursesStudentsByTeacher($id_teacher): Data
    {
        $values = [$id_teacher];
        $stm = 'select C.name as class_name, c.color, c.id_class, c.id_course,
                CO.name as course_name, CO.active, CO.date_end, CO.date_start, CO.description, count(distinct E.id_student) as students
                from class as C
                INNER JOIN courses as CO ON C.id_course = CO.id_course
                INNER JOIN enrollment AS E ON E.id_course = C.id_course
                WHERE E.status = 1 and C.id_teacher = ?
                GROUP BY C.name, c.color, c.id_class, c.id_course, CO.name, CO.active, CO.date_end, CO.date_start, CO.description';
        $this->data = self::doQuery($stm, $values);
        return $this->data;
    }
    public function getClassesByStudent($id_student): Data
    {
        $values = [$id_student];
        $stm = 'select C.id_class, C.name as class_name, C.id_course, C.id_teacher, CO.name as course_name, CO.active, CO.description,
                T.name as teacher_name, T.surname, T.email, E.status
                from class AS C INNER JOIN enrollment AS E ON C.id_course = E.id_course INNER JOIN teachers AS T ON T.id_teacher = C.id_teacher
                INNER JOIN courses as CO ON C.id_course = CO.id_course WHERE  active = 1 and status = 1 and id_student = ?';
        $this->data = self::doQuery($stm, $values);
        return $this->data;
    }
    public function getAllClassDatabyId($id_class):Data{
        $values=[$id_class];
        $stm = 'SELECT C.id_class, C.name as class_name, C.id_course, C.id_teacher, C.color,
        CO.name as course_name, CO.active, CO.description, CO.date_start, CO.date_end,
        T.name as teacher_name, T.surname, T.email,
        P.continuous_assessment as works, P.exams
        FROM class AS C
        INNER JOIN teachers AS T ON T.id_teacher = C.id_teacher
        INNER JOIN courses as CO ON C.id_course = CO.id_course
        INNER JOIN percentage as P ON C.id_class = P.id_class
        WHERE C.id_class = ?';
        $this->data=self::doQuery($stm, $values);
        return $this->data;
    }
    public function getStudentsByClass($id_class):Data{
        $values = [$id_class];
        $stm='SELECT C.id_class, C.name as class_name, C.color, C.id_course, C.id_teacher,
        CO.name as course_name, CO.active, CO.description, CO.date_start, CO.date_end,
        T.name as teacher_name, T.surname as teacher_surname, T.email as teacher_email, T.telephone as teacher_telephone,
        S.id as id_student, S.name as student_name, S.surname as student_surname, S.email as student_email, S.telephone as student_telephone,
        P.continuous_assessment as work, P.exams,
        E.status
        FROM class as C
        INNER JOIN courses as CO ON c.id_course = CO.id_course
        INNER JOIN teachers as T ON C.id_teacher = T.id_teacher
        INNER JOIN percentage as P ON C.id_class = P.id_class
        INNER JOIN enrollment as E ON C.id_course = E.id_course
        INNER JOIN students as S ON S.id = E.id_student
        WHERE C.id_class = ?';
        $this->data = self::doQuery($stm, $values);
        return $this->data;
    }

    //Subjects
    public function getAllWorksByCourse($id_course):Data{
        $values = [$id_course];
        $stm = 'SELECT DISTINCT name, deadline, description, id_class FROM works
                WHERE id_class IN (SELECT DISTINCT id_class FROM class where id_course = ?)';
        $this->data = self::doQuery($stm, $values);
        return $this->data;
    }
    public function getAllWorksByCourseStudent($id_course, $id_student):Data{
        $values = [$id_course, $id_student];
        $stm = 'SELECT name, deadline, description, id_class FROM works
                WHERE id_class IN (SELECT DISTINCT id_class FROM class where id_course = ?) and id_student = ?';
        $this->data = self::doQuery($stm, $values);
        return $this->data;
    }
    public function getAllExamsByCourse($id_course):Data{
        $values = [$id_course];
        $stm = 'SELECT DISTINCT name, deadline, description, id_class FROM exams
                WHERE id_class IN (SELECT DISTINCT id_class FROM class where id_course = ?)';
        $this->data = self::doQuery($stm, $values);
        return $this->data;
    }
    public function getAllExamsByCourseStudent($id_course, $id_student):Data{
        $values = [$id_course, $id_student];
        $stm = 'SELECT name, deadline, description, id_class FROM exams
                WHERE id_class IN (SELECT DISTINCT id_class FROM class where id_course = ?) and id_student = ?';
        $this->data = self::doQuery($stm, $values);
        return $this->data;
    }
    public function getAllWorksByCourseStudentExtended($id_course, $id_student):Data{
        $values=[$id_course, $id_student];
        $stm = 'SELECT W.id_work, W.name as subject_name, deadline as subject_deadline, W.description as subject_description, W.mark, W.id_class, c.name as class_name,
                color as class_color, T.id_teacher, T.name as teacher_name, T.surname as teacher_surname, T.email as teacher_email, Co.id_course, Co.name as course_name,
                Co.date_start as course_date_start, Co.date_end as course_date_end, Co.description as course_description, p.continuous_assessment, p.exams, "work" as type,
                S.id as id_student, S.name as student_name, S.surname as student_surname, S.email as student_email, S.telephone as student_telephone
                FROM works as W
                INNER JOIN class as C ON W.id_class = C.id_class
                INNER JOIN teachers AS T ON C.id_teacher = T.id_teacher
                INNER JOIN courses AS Co ON C.id_course = Co.id_course
                INNER JOIN percentage as P ON P.id_class = C.id_class
                INNER JOIN students as S ON S.id = W.id_student
                WHERE C.id_course = ? and id_student = ?';
        $this->data = self::doQuery($stm, $values);
        return $this->data;
    }
    public function getAllExamsByCourseStudentExtended($id_course, $id_student):Data{
        $values=[$id_course, $id_student];
        $stm = 'SELECT W.id_exam, W.name as subject_name, deadline as subject_deadline, W.description as subject_description, W.mark, W.id_class, c.name as class_name,
                color as class_color, T.id_teacher, T.name as teacher_name, T.surname as teacher_surname, T.email as teacher_email, Co.id_course, Co.name as course_name,
                Co.date_start as course_date_start, Co.date_end as course_date_end, Co.description as course_description, p.continuous_assessment, p.exams, "exam" as type,
                S.id as id_student, S.name as student_name, S.surname as student_surname, S.email as student_email, S.telephone as student_telephone
                FROM exams as W
                INNER JOIN class as C ON W.id_class = C.id_class
                INNER JOIN teachers AS T ON C.id_teacher = T.id_teacher
                INNER JOIN courses AS Co ON C.id_course = Co.id_course
                INNER JOIN percentage as P ON P.id_class = C.id_class
                INNER JOIN students as S ON S.id = W.id_student
                WHERE C.id_course = ? and id_student = ?';
        $this->data = self::doQuery($stm, $values);
        return $this->data;
    }

    //Insert para multiples sentencias
    public function insertMultiple(array $data, $table): bool
    {
        return DBAlias::table($table)->insert($data);
    }
    //Update para multiples tablas
    public function updateMultiple(string $table, array $data, array $attributes){
        return DBAlias::table($table)->where($attributes)->update($data);
    }

    private function doQuery($stm, array $values=[]): Data
    {
        $data = new Data();
        $data->status = false;
        $data->res = [];
        $data->len = 0;
        try{
            $res = DBAlias::connection('mysql')->select($stm, $values);
            if(!isset($res)){
                throw new Exception('Error JoinQueries');
            }else{
                $data->status = true;
                $data->res = $res;
                $data->len = count($res);
            }
        }catch(Exception $e){
            $data->err = $e;
            $data->status = false;
            dd($e->getMessage());
        } finally {
            return $data;
        }
    }
}
