<?php


namespace App\Models;

use Exception;
use App\Entities\Data;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\DB as DBAlias;

class DbQueries
{
    protected $data;
    private $table;

    public function __construct($table){
        $this->data = new Data();
        $this->table = $table;
    }

    protected function getAll() {
        $this->data = new Data();
        try{
            $res = DB::connection('mysql')->select('select * from '.$this->table);
            $this->data->len = count($res);
            $this->data->res = $res;
            $this->data->status = true;
        }catch(Exception $e){
            $this->data->err = $e;
            $this->data->status = false;
            dd($e->getMessage());
        } finally {
            return $this->data;
        }
    }

    protected function getByAttribute(string $col, string $val) {
        $this->data = new Data();
        $values = [$val];
        $stm = "SELECT * FROM ".$this->table." WHERE ".$col." = ?";
        $this->data = self::doQuery($stm, $values);
        return $this->data;
    }
    protected function getByAttributes($col1, $col2, $val1, $val2, string $operator = 'and') {
        $this->data = new Data();
        $values = [$val1, $val2];
        $stm = "SELECT *  FROM ". $this->table ." WHERE ". $col1 .' = ? '.$operator.' '.$col2.' = ?';
        $this->data = self::doQuery($stm, $values);
        return $this->data;
    }

    //UPDATE METHOD
    protected function updateValue($attribute, $new_value, $col, $val) {
        $this->data = new Data();
        $values = [$new_value, $val];
        $stm = "UPDATE ".$this->table." SET ".$attribute." = ? WHERE ".$col." = ?";
        $this->data = self::doQuery($stm, $values);
        return $this->data;
    }
    protected function updateMultiple(string $table, array $data, $id_name, $id_value){
        return DBAlias::table($table)->where($id_name,$id_value)->update($data);
    }
    private function doQuery($stm, array $values=[]): Data
    {
        $data = new Data();
        $data->status = false;
        $data->res = [];
        $data->len = 0;
        try{
            $res = DBAlias::connection('mysql')->select($stm, $values);
            if(!isset($res)){
                throw new Exception('Error JoinQueries');
            }else{
                $data->status = true;
                $data->res = $res;
                $data->len = count($res);
            }
        }catch(Exception $e){
            $data->err = $e;
            $data->status = false;
            dd($e->getMessage());
        } finally {
            return $data;
        }
    }

}
