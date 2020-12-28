<?php


namespace App\Models;


use App\Entities\Data;
use Illuminate\Support\Facades\DB;
use Exception;
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
        $this->data =  new Data();
        $values=[$name, $description, $date_start, $date_end, $active];
        $stm = 'INSERT INTO courses (name, description, date_start, date_end, active) VALUES (?,?,?,?,?)';
        try{
            $this->data->affected_rows = DB::connection('mysql')->insert($stm, $values);
            $this->data->status = true;
        }catch(Exception $e){
            $this->data->err = $e;
            $this->data->status = false;
        } finally {
            $this -> data -> affected_rows = 0;
            return $this->data;
        }
    }

    public function deleteById($id) {
        $this->data =  new Data();
        $values = [$id];
        $stm = 'DELETE FROM courses WHERE id_course = ?';
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
