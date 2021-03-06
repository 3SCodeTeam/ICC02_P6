<?php


namespace App\Models;


use Illuminate\Support\Facades\DB;

class Notifications extends DbQueries
{
    public $data;
    public function __construct()
    {
        parent::__construct('Notifications');
    }
    public function getAll(){$this->data = parent::getAll();}
    public function getById(int $value) { $this->data = parent::getByAttribute('id_notifications',$value);}
    public function getByIdStudent($value) {$this->data = parent::getByAttribute('id_student',$value);}

    public function updateValue($attribute, $new_value, $col, $val) {
        $this->data = parent::updateValue($attribute, $new_value, $col, $val);
    }
    public function updateValueByIdNotification($attribute, $new_value, $value){
        $this->data = parent::updateValue($attribute, $new_value, 'id_notification', $value);
    }
    public function updateValueByIdStudent($attribute, $new_value, $value){
        $this->data = parent::updateValue($attribute, $new_value, 'id_student', $value);
    }
    public function updateAllValuesByIdStudent($work, $exam, $continuous_assessment, $final, $id_student){
        $values = [$work, $exam, $continuous_assessment, $final, $id_student];
        $stm = 'UPDATE notifications SET work = ?, exam = ?, continuous_assessment = ?, final_note = ? WHERE id_student = ?';
        try{
            $res = DB::connection('mysql')->update($stm, $values);
        }catch(Exception $e){
            $this->data->err = $e;
            $this->data->status = false;
            dd($e->getMessage());
        }
        $this->data->status = true;
        $this->data->affected_rows = $res;
    }

    public function insertValues($id_student, $work, $exam, $continuous_assessment, $final_note) {
        $values = [$id_student, $work, $exam, $continuous_assessment, $final_note];
        $stm = 'INSERT INTO notifications (id_student, work, exam, continuous_assessment, final_note) VALUES (?, ?, ?, ?, ?)';
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
        $stm = 'DELETE FROM notifications WHERE id_notification = ?';
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
