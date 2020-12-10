<?php


namespace App\Models;


use Illuminate\Support\Facades\DB;

class Exams extends DbQueries
{
    public $data;
    public function __construct()
    {
        parent::__construct('exams');
    }
    public function getAll(){$this->data = parent::getAll();}
    public function getById(int $value) { $this->data = parent::getByAttribute('id_exam',$value);}
    public function getByIdClass($value) {$this->data = parent::getByAttribute('id_class',$value);}
    public function getByIdStudent($value) {$this->data = parent::getByAttribute('id_student',$value);}
    public function getByName($value) {$this->data = parent::getByAttribute('name',$value);}
    public function getByIdClassAndIdStudent($value1, $value2) {$this->data = parent::getByAttributes('id_class', 'id_student',$value1, $value2, 'and');}

    public function updateValue($attribute, $new_value, $col, $val) {
        $this->data = parent::updateValue($attribute, $new_value, $col, $val);
    }
    public function updateValueById($attribute, $new_value, $value){
        $this->data = parent::updateValue($attribute, $new_value, 'id_exam', $value);
    }

    public function insertValues($id_class, $id_student, $name, $mark) {
        $values = [$id_class, $id_student, $name, $mark];
        $stm = 'INSERT INTO exams (id_class, id_student, name, mark) VALUES (?, ?, ?, ?)';
        try{
            $res = DB::connection('mysql')->insert($stm, $values);
        }catch(Exception $e){
            $this->data->err = $e;
            $this->data->status = false;
            dd($e->getMessage());
        }
        $this->data->status = true;
        $this->data->affected_rows = $res;
    }

    public function deleteById($id_notification){
        $values = [$id_notification];
        $stm = 'DELETE FROM exams WHERE id_exam = ?';
        try {
            $res = DB::connection('mysql')->delete($stm, $values);
        } catch (Exception $e) {
            $this->data->err = $e;
            $this->data->status = false;
            dd($e->getMessage());
        }
        $this->data->status = true;
        $this->data->affected_rows = $res;
    }
    public function getDistinctByIdClass($id_class){
        $values=[$id_class];
        $stm = 'SELECT DISTINCT name FROM exams WHERE id_class = ?';
        try {
            $res = DB::connection('mysql')->delete($stm, $values);
        } catch (Exception $e) {
            $this->data->err = $e;
            $this->data->status = false;
            dd($e->getMessage());
        }
        $this->data->status = true;
        $this->data->affected_rows = $res;
    }
}
