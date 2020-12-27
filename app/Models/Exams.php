<?php


namespace App\Models;


use App\Entities\Data;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\DB as DBAlias;

class Exams extends DbQueries
{
    public $data;
    public function __construct()
    {
        parent::__construct('exams');
    }
    public function getAll(): Data
    {return $this->data = parent::getAll();}
    public function getById($value) { $this->data = parent::getByAttribute('id_exam',$value);}
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
        $stm = 'INSERT INTO exams (id_class, id_student, name, mark, description, deadline) VALUES (?, ?, ?, ?, ?, ?)';
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

    public function deleteById($id_exam){
        $values = [$id_exam];
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
    public function deleteByIdClassName($id_class, $name){
        $values = [$id_class, $name];
        $stm = 'DELETE FROM exams WHERE id_class = ? and name = ?';
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
        $stm = 'SELECT DISTINCT name, deadline, description, id_class FROM exams WHERE id_class = ?';
        $this->data = self::doQuery($stm, $values);
    }
    public function getDistinctByIdClassName($id_class, $name){
        $values=[$id_class, $name];
        $stm = 'SELECT DISTINCT name, deadline, description, id_class FROM exams WHERE id_class = ? and name = ?';
        $this->data = self::doQuery($stm, $values);
    }
    private function doQuery($stm, array $values=null){
        $data = new Data();
        $data->status = false;
        $data->res = [];
        $data->len = 0;
        try{
            $res = DBAlias::connection('mysql')->select($stm, $values);
            if(!isset($res)){
                throw new Exception('Error getStudentsByClass query');
            }
            $data->status = true;
            $data->res = $res;
            $data->len = count($res);
        }catch(Exception $e){
            $data->err = $e;
            $data->status = false;
            dd($e->getMessage());
        } finally {

            return $data;
        }
    }
}
