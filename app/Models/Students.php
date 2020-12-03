<?php


namespace App\Models;


use Illuminate\Support\Facades\DB;

class Students extends DbQueries
{
    public $data;

    public function __construct()
    {
        parent::__construct('students');
    }

    public function getAll(){$this->data = parent::getAll();}
    public function getById(int $id) { $this->data = parent::getByAttribute('id',$id);}
    public function getByEmail($email) {$this->data = parent::getByAttribute('email',$email);}
    public function getByUsername(string $username) {$this->data = parent::getByAttribute('username',$username);}
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
            $this->data->stat = false;
            dd($e->getMessage());
        }
        $this->data->stat = true;
        $this->data->affected_rows = $res;
    }

    public function deleteById($id){
        $values = [$id];
        $stm = 'DELETE FROM students WHERE id = ?';
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