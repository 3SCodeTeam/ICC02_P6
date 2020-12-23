<?php


namespace App\Utils;


use App\Models\Exams;
use App\Models\Percentages;
use App\Models\Works;

class MarkSTools
{
    public static function getStudentsMarksByClass($id_class, $students){
        $wMod = new Works();
        $eMod = new Exams();
        $weights = self::getClassesMarksWeights($id_class);
        $marks = [];

        foreach ($students as $s){
            $wMod ->getByIdClassAndIdStudent($id_class,$s->id_student);
            $eMod ->getByIdClassAndIdStudent($id_class,$s->id_student);

            $worksMarks = self::getMarks($wMod);
            $examsMarks = self::getMarks($eMod);

            if($examsMarks === '----' || $worksMarks === '----'){
                $marks[$s->id_student] = ['exam'=>$examsMarks, 'work'=>$worksMarks, 'global'=>'----', 'weights'=>$weights];
            }else{
                $global = $examsMarks*$weights['exams'] + $worksMarks*$weights['works'];
                $marks[$s->id_student] = ['exam'=>$examsMarks, 'work'=>$worksMarks, 'global'=>$global, 'weights'=>$weights];
            }
        }
        return $marks;
    }
    public static function getClassesMarksWeights($id_class){
        $pMod = new Percentages();
        $pMod->getByIdClass($id_class);
        $eWeight = $pMod->data->res[0]->exams;
        $wWeight = $pMod->data->res[0]->continuous_assessment;

        return ['exams'=>$eWeight, 'works'=>$wWeight];
    }
    public static function getClassesMarksByStudent($classes, $id_Student){
        $wMod = new Works();
        $eMod = new Exams();
        $marks = [];

        foreach ($classes as $c){
            $wMod->getByIdClassAndIdStudent($c->id_class,$id_Student);
            $eMod->getByIdClassAndIdStudent($c->id_class, $id_Student);

            $worksMarks = self::getMarks($wMod);
            $examsMarks = self::getMarks($eMod);

            $weights = self::getClassesMarksWeights($c->id_class);

            if($examsMarks === '----' || $worksMarks === '----'){
                $marks[$c->id_class] = ['exam'=>$examsMarks, 'work'=>$worksMarks, 'global'=>'----', 'weights'=>$weights];
            }else{
                $global = $examsMarks*$weights['exams'] + $worksMarks*$weights['works'];
                $marks[$c->id_class] = ['exam'=>$examsMarks, 'work'=>$worksMarks, 'global'=>$global, 'weights'=>$weights];
            }
        }
        return $marks;
    }
    private static function getMarks($mod){
        $marks = 0;
        foreach ($mod->data->res as $w){
            if($w->mark < 0){
                $marks = '----';
                break;
            }
            $marks += $w->mark;
        }
        if(!($marks == '----')){
            return  ($marks / $mod->data->len);
        }
        return $marks;
    }
}
