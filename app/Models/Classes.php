<?php


namespace App\Models;


use App\Entities\Data;
use Illuminate\Support\Facades\DB;
use Exception;

class Classes extends DbQueries
{
    public $data;
    public function __construct()
    {
        parent::__construct('class');
    }
    public function getAll(){$this->data = parent::getAll();}
    public function getById(int $value) { $this->data = parent::getByAttribute('id_class',$value);}
    public function getByTeacher($value) {$this->data = parent::getByAttribute('id_teacher',$value);}
    public function getByIdCourse($value) {$this->data = parent::getByAttribute('id_course',$value);}
    public function getByIdSchedule($value) {$this->data = parent::getByAttribute('id_schedule',$value);}
    public function getByName(string $value) {$this->data = parent::getByAttribute('name',$value);}
    public function getByColor(string $value) {$this->data = parent::getByAttribute('color',$value);}
    public function getByNameAndCourse($name, $id_course){$this->data = parent::getByAttributes('name','id_course',$name, $id_course,'and');}

    public function updateValue($attribute, $new_value, $col, $val) {
        $this->data = parent::updateValue($attribute, $new_value, $col, $val);
    }
    public function updateValueById($attribute, $new_value, $val){
        $this->data = parent::updateValue($attribute, $new_value, 'id_class', $val);
    }

    public function getIdClass($id_teacher, $id_course, $id_schedule, $name, $color){
        $this->data = new Data();
        $values = [$id_teacher, $id_course, $id_schedule, $name, $color];
        $stm ='SELECT id_class, id_teacher, id_course, id_schedule, name, color FROM class
        WHERE id_teacher = ? and id_course = ? and id_schedule = ? and name = ? and color = ?';
        try{
            $this->data->res = DB::connection('mysql')->select($stm, $values);
            $this->data->status = true;
        }catch(Exception $e){
            $this->data->err = $e;
            $this->data->status = false;
        }finally{
            return $this->data;
        }
    }

    public function insertValues($id_teacher, $id_course, $id_schedule, $name, $color) {
        $this->data = new Data();
        $values=[$id_teacher, $id_course, $id_schedule, $name, $color];
        $stm ='INSERT INTO class (id_teacher, id_course, id_schedule, name, color) VALUES (?,?,?,?,?)';
        try{
            $res = DB::connection('mysql')->insert($stm, $values);
        }catch(Exception $e){
            $this->data->err = $e;
            $this->data->status = false;
            dd($e->getMessage());
        } finally {
            $this->data->status = true;
            $this->data->affected_rows = $res;
        }
    }

    public function deleteById($id) {
        $values = [$id];
        $stm = 'DELETE FROM class WHERE id_course = ?';
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
    public function deleteByScheduleId($id) {
        $values = [$id];
        $stm = 'DELETE FROM class WHERE id_schedule = ?';
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
