<?php


namespace App\Models;


use App\Entities\Data;
use Illuminate\Support\Facades\DB;

class Students extends DbQueries
{
    public $data;

    public function __construct()
    {
        parent::__construct('students');
        $this->data = new Data();
    }

    public function getAll(){$this->data = parent::getAll();}
    public function getById($value) { $this->data = parent::getByAttribute('id',$value);}
    public function getByEmail($value) {$this->data = parent::getByAttribute('email',$value);}
    public function getByUsername(string $value) {$this->data = parent::getByAttribute('username',$value);}
    public function getByNif($value) {$this->data = parent::getByAttribute('nif',$value);}
    public function updateValue($attribute, $new_value, $col, $val) {
        $this->data = parent::updateValue($attribute, $new_value, $col, $val);
    }
    public function updateValueById($attribute, $new_value, $val){
        $this->data = parent::updateValue($attribute, $new_value, 'id', $val);
    }

    public function insertValues($date_registered, $email, $name, $nif, $pass, $surname, $telephone, $username) {
        $values = [$date_registered, $email, $name, $nif, $pass, $surname, $telephone, $username];
        $stm = 'INSERT INTO students (date_registered, email, name, nif, pass, surname, telephone, username) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
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

    public function deleteById($id){
        $values = [$id];
        $stm = 'DELETE FROM students WHERE id = ?';
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
