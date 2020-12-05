<?php

namespace App\Models;

use App\Entities\Data;
use Illuminate\Support\Facades\DB;

class UsersAdmin extends DbQueries
{
    public $data;

    public function __construct()
    {
        parent::__construct('users_admin');
        $this->data = new Data();
    }

    public function getAll(){$this->data = parent::getAll();}
    public function getById(int $id) { $this->data = parent::getByAttribute('id_user_admin',$id);}
    public function getByEmail($email) {$this->data = parent::getByAttribute('email',$email);}
    public function getByUsername(string $username) {$this->data = parent::getByAttribute('username',$username);}
    public function updateValue($attribute, $new_value, $col, $val) {
        $this->data = parent::updateValue($attribute, $new_value, $col, $val);
    }
    public function updateValueById($attribute, $new_value, $val){
        $this->data = parent::updateValue($attribute, $new_value, 'id_user_admin', $val);
    }

    public function insertValues($username, $name, $email, $password) {
        $values = [$username, $name, $email, $password];
        $stm = 'INSERT INTO users_admin (username, name, email, password) VALUES (?, ?, ?, ?)';
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

    public function deleteById($id_user_admin){
        $values = [$id_user_admin];
        $stm = 'DELETE FROM users_admin WHERE id_user_admin = ?';
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
