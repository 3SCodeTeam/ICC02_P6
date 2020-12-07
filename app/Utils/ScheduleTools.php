<?php


namespace App\Utils;


use App\Models\JoinQueries;
use DateInterval;
use DateTime;

class ScheduleTools
{
   private static $hours = ['08:00', '09:00', '10:00', '11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00'];
   private static $dow = ['HORA','LUNES', 'MARTES','MIÉRCOLES', 'JUEVES', 'VIERNES', 'SÁBADO', 'DOMINGO'];


    public static function buildMonthSchedule($id_student)
   {
       date_default_timezone_set('Europe/London');
       $date = new DateTime(date('Y-m-d'));

       $plus1Day = new DateInterval('P1D');
       $currentMonthFirstDay = self::getCurrentMonthFirstDate($date); //Primer día del mes.
       $weekNum = $currentMonthFirstDay->format("W");

       $dow = ['SEMANA', 'LUNES', 'MARTES', 'MIÉRCOLES', 'JUEVES', 'VIERNES', 'SÁBADO', 'DOMINGO'];
       $ctrlDate = self::getFirstDate($currentMonthFirstDay);

       $monthData= [];
       $weekData = [];
       $monthData[] = $dow;

       for ($i = $weekNum; $i < intval($weekNum) + 6; $i++) {
           foreach ($dow as $d) {
               if ($d === 'SEMANA') {
                   if ($i > 53) {
                       $weekData[] = ['col' => $d, 'value' => '1'];
                   } else {
                       $weekData[] = ['col' => $d, 'value' => $i];
                   }
               } else {
                   $weekData[] = ['col' => $d, 'value' => self::getClassesByDay($ctrlDate, $id_student), 'date'=>$ctrlDate->format('Y-m-d-l')];
                   $ctrlDate->add($plus1Day);
               }
           }
           $monthData[] = $weekData;
           $weekData = [];
       }
       return $monthData;
   }
    public static function buildWeekSchedule($id_student){
        $plus1Day = new DateInterval('P1D');
        $date = new DateTime(date('Y-m-d'));
        $ctrlDate = self::getFirstDate($date); //Fecha del primer día de la semana actual.

        $weekData=[];
        $weekData[]=self::$dow;

        foreach(self::$hours as $h){
            $hourData=[];
            foreach(self::$dow as $d){
                if($d==='HORA'){
                    $hourData[]=['col'=>$d, 'value'=>$h];
                }else{
                    $hourData[]=['col'=>$d, 'value'=>self::getClassesByHour($ctrlDate, $h, $id_student), 'date'=>$ctrlDate, 'hour'=>$h];
                    $ctrlDate->add($plus1Day);
                }
            }
            $weekData[]=$hourData;
            $ctrlDate = self::getFirstDate($date);
        }
        return $weekData;
    }
    public static function buildDaySchedule($id_student){
        $date = new DateTime(date('Y-m-d'));

        $col=['HORA', $date->format("d/m/Y")];

        $dayData=[];
        $dayData[]=$col;

        foreach(self::$hours as $h){
            $hourData=[];
            foreach($col as $c){
                if($c === 'HORA'){
                    $hourData[]=['col'=>$c, 'value'=>$h];
                }else{
                    $hourData[]=['col'=>$c, 'value'=>self::getClassesByHour($date, $h, $id_student), 'hour'=>$h];
                }
            }
            $dayData[]=$hourData;
        }
        return $dayData;
    }

   public static function setCurrentDate(){
       //self::$selectedCurrentDate = new DateTime(date('Y-m-d'));
   }

   private static function getCurrentMonthFirstDate($date){//Obtenie la fecha del primer día del mes.
       $month = $date->format("m");
       $year = $date->format("Y");
       $newDateString = $year.'-'.$month.'-01';
       $date = new DateTime($newDateString);
       return $date;
   }
    private static function getFirstDate($date){//Devuelve la fecha del primer día de la semana actual.
        $days = 0;
        switch($date->format("D")){
            case 'Mon': return $date;
            case 'Tue': $days+=1; break;
            case 'Wed': $days+=2; break;
            case 'Thu': $days+=3; break;
            case 'Fry': $days+=4; break;
            case 'Sat': $days+=5; break;
            case 'Sun': $days+=6; break;
        }
        return $date->sub(self::subDaysToDate($days));
    }

    private static function subDaysToDate($numOfDays){//Subtrae días a una fecha dada
        return new DateInterval('P'.$numOfDays.'D');
    }

    private static function getClassesByHour($date, $hour, $id){
        $mod = new JoinQueries();
        $dayData =[];
        $date = $date->format("Y-m-d");
        $mod->getClassesOfDay($id, $date);
        if($mod->data->len > 0){
            foreach($mod->data->res as $item){
                if(substr($item->time_start,0,5) == $hour){
                    $dayData[]=['color'=>$item->class_color, 'name'=>$item->class_name, 'id'=>$item->id_class];
                }
            }
        }
        return $dayData;
    }

    private static function getClassesByDay($date, $id){
       $mod = new JoinQueries();
       $dayData =[];
       $date = $date->format("Y-m-d");
       $mod->getClassesOfDay($id, $date);
       if($mod->data->len >0){
           foreach($mod->data->res as $item){
               $dayData[]=['color'=>$item->class_color, 'name'=>$item->class_name, 'id'=>$item->id_class];
            }
        }
        return $dayData;
    }
    /*
   SELECT
   S.id_class,
   S.day,
   C.name,
   C.color,
   Co.name
   FROM schedule as S inner JOIN class as C ON S.id_class=C.id_class INNER JOIN courses as Co ON C.id_course = Co.id_course
   WHERE id_course IN (SELECT id_course FROM enrollment WHERE id_student = ?) and S.day = ?;
   */

    /*
        jddayofweek($date);
        0 (Por defecto)	Devuelve el número de día como un entero (0=domingo, 1=lunes, etc.)
        1	Devuelve una cadena que contiene el día de la semana (Inglés-Gregoriano)
        2	Devuelve una cadena que contiene el día de la semana abreviado (Inglés-Gregoriano)
    */
    /*
        DateTime();
        DateTime()->format("W"); weekNum
        DateTime()->format("Y"); year
        DateTime()->format("m"); month
        DateTime()->format("D"); day of the week

        DateTime()->sub(new DateInterval('P10D'));
        DateTime()->add(new DateInterval('P10D'));

        date("Y/m/d")
        date("Y.m.d")
        date("Y-m-d")
        date("l")

    */
}
