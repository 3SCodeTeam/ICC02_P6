<?php


namespace App\Models;


use App\Entities\Data;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\DB as DBAlias;

class Works extends DbQueries
{
    public $data;
    public function __construct()
    {
        parent::__construct('works');
    }
    public function getAll(){$this->data = parent::getAll();}
    public function getById(int $value) { $this->data = parent::getByAttribute('id_work',$value);}
    public function getByIdClass($value) {$this->data = parent::getByAttribute('id_class',$value);}
    public function getByIdStudent($value) {$this->data = parent::getByAttribute('id_student',$value);}
    public function getByName($value) {$this->data = parent::getByAttribute('name',$value);}
    public function getByIdClassAndIdStudent($id_class, $id_student) {$this->data = parent::getByAttributes('id_class', 'id_student',$id_class, $id_student, 'and');}

    public function updateValue($attribute, $new_value, $col, $val) {
        $this->data = parent::updateValue($attribute, $new_value, $col, $val);
    }
    public function updateValueById($attribute, $new_value, $value){
        $this->data = parent::updateValue($attribute, $new_value, 'id_work', $value);
    }

    public function insertValues($id_class, $id_student, $name, $mark) {
        $values = [$id_class, $id_student, $name, $mark];
        $stm = 'INSERT INTO works (id_class, id_student, name, mark, description) VALUES (?, ?, ?, ?, ?)';
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
        $stm = 'DELETE FROM works WHERE id_work = ?';
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
        $stm = 'SELECT DISTINCT name, deadline, description, id_class FROM works WHERE id_class = ?';
        $this->data = self::doQuery($stm,$values);
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
