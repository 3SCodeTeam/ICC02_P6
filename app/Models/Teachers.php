<?php


namespace App\Models;


use Illuminate\Support\Facades\DB;

class Teachers extends DbQueries
{
    public $data;

    public function __construct()
    {
        parent::__construct('teachers');
    }
    public function getAll(){$this->data = parent::getAll();}
    public function getById(int $id) { $this->data = parent::getByAttribute('id_teacher',$id);}
    public function getByEmail($email) {$this->data = parent::getByAttribute('email',$email);}
    //public function getByUsername(string $username) {$this->data = parent::getByAttribute('username',$username);}
    public function updateValue($attribute, $new_value, $col, $val) {
        $this->data = parent::updateValue($attribute, $new_value, $col, $val);
    }
    public function updateValueById($attribute, $new_value, $val){
        $this->data = parent::updateValue($attribute, $new_value, 'id_teacher', $val);
    }

    public function insertValues($name, $surname, $telephone, $nif, $email, $pass) {
        $values = [$name, $surname, $telephone, $nif, $email, $pass];
        $stm = 'INSERT INTO teachers (name, surname, telephone, nif, email, pass) VALUES (?, ?, ?, ?, ?, ?)';
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

    public function deleteById($id_teacher){
        $values = [$id_teacher];
        $stm = 'DELETE FROM teachers WHERE id_teacher = ?';
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
