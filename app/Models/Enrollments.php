<?php


namespace App\Models;


use Illuminate\Support\Facades\DB;

class Enrollments extends DbQueries
{
    public $data;

    public function __construct()
    {
        parent::__construct('enrollment');
    }
    public function getAll(){$this->data = parent::getAll();}
    public function getById(int $value) { $this->data = parent::getByAttribute('id_enrollment',$value);}
    public function getByIdStudent($value) {$this->data = parent::getByAttribute('id_student',$value);}
    public function getByIdCourse($value) {$this->data = parent::getByAttribute('id_course',$value);}
    public function getByStatus(string $value) {$this->data = parent::getByAttribute('status',$value);}
    public function getStudentsByCurseIdAndStatus($course_id, $status){$this->data = parent::getByAttributes('id_courses','status',$course_id, $status, 'and');}
    public function updateValue($attribute, $new_value, $col, $val) {
        $this->data = parent::updateValue($attribute, $new_value, $col, $val);
    }
    public function updateValueById($attribute, $new_value, $val) {
        $this->data = parent::updateValue($attribute, $new_value, 'enrollment', $val);
    }

    public function insertValues($id_enrollment, $id_student, $id_course, $status) {
        $values=[$id_enrollment, $id_student, $id_course, $status];
        $stm = "SELECT id_enrollment, id_student, id_course, status FROM enrollment WHERE id_course = ? and status = ?";
        try{
            $res = DB::connection('mysql')->insert($stm, $values);
        }catch(Exception $e){
            $this->data->err = $e;
            $this->data->stat = false;
            dd($e->getMessage());
        }
        $this->data->stat = true;
        $this->data->affected_rows = $res;
    }
    public function deleteById($id) {
        $values = [$id];
        $stm = 'DELETE FROM enrollment WHERE id_enrollment = ?';
        try{
            $res = DB::connection('mysql')->delete($stm, $values);
        }catch(Exception $e){
            $this->data->err = $e;
            $this->data->stat = false;
            dd($e->getMessage());
        }
        $this->data->stat = true;
        $this->data->affected_rows = $res;
    }
}
