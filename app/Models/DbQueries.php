<?php


namespace App\Models;


use App\Entities\Data;
use Illuminate\Support\Facades\DB;

class DbQueries
{
    protected $data;
    private $table;

    public function __construct($table){
        $this->data = new Data();
        $this->table = $table;
    }

    protected function getAll() {
        try{
            $res = DB::connection('mysql')->select('select * from '.$this->table);
            $this->data->len = count($res);
            $this->data->res = $res;
            $this->data->stat = true;
        }catch(Exception $e){
            $this->data->err = $e;
            $this->data->stat = false;
            dd($e->getMessage());
        } finally {
            return $this->data;
        }
    }

    protected function getByAttribute(string $col, string $val) {
        $values = [$val];
        $stm = "SELECT * FROM ".$this->table." WHERE ".$col." = ?";
        try{
            $res = DB::connection('mysql')->select($stm, $values);
            $this->data->len = count($res);
            $this->data->res = $res;
            $this->data->stat = true;
        }catch(Exception $e){
            $this->data->err = $e;
            $this->data->stat = false;
            dd($e->getMessage());
        } finally {
            return $this->data;
        }
    }
    protected function getByAttributes($col1, $col2, $val1, $val2, string $operator = 'and') {
        $values = [$val1, $val2];
        $stm = "SELECT *  FROM ". $this->table ." WHERE ". $col1 .' = ? '.$operator.' '.$col2.' = ?';
        try{
            $res = DB::connection('mysql')->select($stm, $values);
            $this->data->len = count($res);
            $this->data->res = $res;
            $this->data->stat = true;
        }catch(Exception $e){
            $this->data->err = $e;
            $this->data->stat = false;
            dd($e->getMessage());
        } finally {
            return $this->data;
        }
    }

    //UPDATE METHOD
    public function updateValue($attribute, $new_value, $col, $val) {
        $values = [$new_value, $val];
        $stm = "UPDATE ".$this->table." SET ".$attribute." = ? WHERE ".$col." = ?";
        try{
            $res = DB::connection('mysql')->update($stm, $values);
            $this->data->stat = true;
            $this->data->affected_rows = $res;
        }catch(Exception $e){
            $this->data->err = $e;
            $this->data->stat = false;
            dd($e->getMessage());
        } finally {
            return $this->data;
        }
    }

}
