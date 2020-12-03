<?php


namespace App\Models;


use Illuminate\Support\Facades\DB;

class Percentages extends DbQueries
{
    public $data;
    public function __construct()
    {
        parent::__construct('percentage');
    }
    public function getAll(){$this->data = parent::getAll();}
    public function getById(int $value) { $this->data = parent::getByAttribute('id_percentage',$value);}
    public function getByIdCourse($value) {$this->data = parent::getByAttribute('id_course',$value);}
    public function getByIdClass(string $value) {$this->data = parent::getByAttribute('id_class',$value);}

    public function updateValue($attribute, $new_value, $col, $val) {
        $this->data = parent::updateValue($attribute, $new_value, $col, $val);
    }
    public function updateValueById($attribute, $new_value, $val){
        $this->data = parent::updateValue($attribute, $new_value, 'id_percentage', $val);
    }
    public function updateValueByIdClass($attribute, $new_value, $val){
        $this->data = parent::updateValue($attribute, $new_value, 'id_class', $val);
    }

    public function insertValues($id_course, $id_class, $continuous_assessment, $exams) {
        $values = [$id_course, $id_class, $continuous_assessment, $exams];
        $stm = 'INSERT INTO percentage (id_course, id_class, continuous_assessment, exams) VALUES (?, ?, ?, ?)';
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

    public function deleteById($id_percentage){
        $values = [$id_percentage];
        $stm = 'DELETE FROM percentage WHERE id_percentage = ?';
        try {
            $res = DB::connection('mysql')->delete($stm, $values);
        } catch (Exception $e) {
            $this->data->err = $e;
            $this->data->stat = false;
            dd($e->getMessage());
        }
        $this->data->stat = true;
        $this->data->affected_rows = $res;
    }
}
