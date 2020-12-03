<?php


namespace App\Models;


use Illuminate\Support\Facades\DB;

class Courses extends DbQueries
{
    public $data;
    public function __construct()
    {
        parent::__construct('courses');
    }
    public function getAll(){$this->data = parent::getAll();}
    public function getById(int $value) { $this->data = parent::getByAttribute('id_course',$value);}
    public function getByName($value) {$this->data = parent::getByAttribute('name',$value);}
    public function getByDateStart($value) {$this->data = parent::getByAttribute('date_start',$value);}
    public function getByDateEnd($value) {$this->data = parent::getByAttribute('date_end',$value);}
    public function getByStatus(string $value) {$this->data = parent::getByAttribute('active',$value);}
    public function updateValue($attribute, $new_value, $col, $val) {
        $this->data = parent::updateValue($attribute, $new_value, $col, $val);
    }
    public function updateValueById($attribute, $new_value, $val){
        $this->data = parent::updateValue($attribute, $new_value, 'id_course', $val);
    }

    public function insertValues($name, $description, $date_start, $date_end, $active) {
        $values=[$name, $description, $date_start, $date_end, $active];
        $stm = 'INSERT INTO courses (name, description, date_start, date_end, active) VALUES (?,?,?,?,?)';
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
        $stm = 'DELETE FROM courses WHERE id_course = ?';
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
