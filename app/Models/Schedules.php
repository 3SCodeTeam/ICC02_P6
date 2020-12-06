<?php


namespace App\Models;


use Illuminate\Support\Facades\DB;

class Schedules extends DbQueries
{
    public $data;

    public function __construct()
    {
        parent::__construct('Schedule');
    }
    public function getAll(){$this->data = parent::getAll();}
    public function getById(int $value) { $this->data = parent::getByAttribute('id_schedule',$value);}
    public function getByIdClass($value) {$this->data = parent::getByAttribute('id_class',$value);}
    public function getByDay(string $value) {$this->data = parent::getByAttribute('day',$value);}
    public function updateValue($attribute, $new_value, $col, $val) {
        $this->data = parent::updateValue($attribute, $new_value, $col, $val);
    }
    public function updateValueById($attribute, $new_value, $val) {
        $this->data = parent::updateValue($attribute, $new_value, 'id_enrollment', $val);
    }
    public function maxByIdClass($val){
        $values=[$val];
        $stm = "SELECT  max(id_schedule), id_class, time_start, time_end, day FROM schedule WHERE id_class = ?";
        try{
            $res = DB::connection('mysql')->select($stm, $values);
        }catch(Exception $e){
            $this->data->err = $e;
            $this->data->status = false;
            dd($e->getMessage());
        }
        $this->data->status = true;
        $this->data->affected_rows = $res;
    }
    public function insertValues($id_class, $time_start, $time_end, $day) {
        $values=[$id_class, $time_start, $time_end, $day];
        $stm = 'INSERT INTO schedule (id_class, time_start, time_end, day) VALUES (?, ?, ?, ?)';
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

    public function deleteById($id) {
        $values = [$id];
        $stm = 'DELETE FROM schedule WHERE id_schedule = ?';
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
