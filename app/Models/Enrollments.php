<?php


namespace App\Models;

use Exception;
use App\Entities\Data;
use Illuminate\Support\Facades\DB;

class Enrollments extends DbQueries
{
    public $data;

    public function __construct()
    {
        parent::__construct('enrollment');
        $this ->data = new Data();
    }
    public function getAll(){$this->data = parent::getAll();}
    public function getById(int $value) { $this->data = parent::getByAttribute('id_enrollment',$value);}
    public function getByIdStudent($value) {$this->data = parent::getByAttribute('id_student',$value);}
    public function getByIdCourse($value) {$this->data = parent::getByAttribute('id_course',$value);}
    public function getByStatus(string $value) {$this->data = parent::getByAttribute('status',$value);}
    public function getByCurseIdAndStatus($course_id, $status){$this->data = parent::getByAttributes('id_courses','status',$course_id, $status, 'and');}
    public function getByStudentIdAndStatus($id_student, $status){$this->data = parent::getByAttributes('id_student','status',$id_student, $status,'and');}
    public function getByCourseIdAndStudentId($id_course, $id_student){$this->data = parent::getByAttributes('id_course', 'id_student',$id_course, $id_student, 'and');}
    public function updateValue($attribute, $new_value, $col, $val) {
        $this->data = parent::updateValue($attribute, $new_value, $col, $val);
    }
    public function updateValueById($attribute, $new_value, $val) {
        $this->data = parent::updateValue($attribute, $new_value, 'id_enrollment', $val);
    }

    public function insertValues( $id_student, $id_course, $status) {
        $this->data = new Data();
        $values=[$id_student, $id_course, $status];
        $stm = "INSERT INTO enrollment (id_student, id_course, status) values (?, ?, ?)";
        try{
            $res = DB::connection('mysql')->insert($stm, $values);
        }catch(Exception $e){
            $this->data->err = $e;
            $this->data->status = false;
        }
        $this->data->status = true;
        $this->data->affected_rows = $res;
    }
    public function deleteById($id) {
        $this->data = new Data();
        $values = [$id];
        $stm = 'DELETE FROM enrollment WHERE id_enrollment = ?';
        try{
            $res = DB::connection('mysql')->delete($stm, $values);
        }catch(Exception $e){
            $this->data->err = $e;
            $this->data->status = false;
            dd($e->getMessage());
        }
        $this->data->status = true;
        $this->data->affected_rows = $res;
    }
}
