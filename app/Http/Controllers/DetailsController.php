<?php


namespace App\Http\Controllers;


use App\Models\Classes;
use App\Models\Courses;
use App\Models\Exams;
use App\Models\JoinQueries;
use App\Models\Percentages;

use App\Models\Works;

class DetailsController extends Controller
{
    public static function studentsDetails($id){
        $mod = new JoinQueries();
        $students = $mod->getStudentsByCourse($id);
        $mod = new Courses();
        $mod->getById($id);
        $course = $mod->data->res[0];

        $course=['id_course'=>$course->id_course, 'name'=>$course->name, 'date_start'=>$course->date_start, 'date_end'=>$course->date_end];

        return view('details', ['selectedMenu'=>'studentsDetails', 'course'=>$course,'students'=>$students->res]);
    }
    public static function classesDetails($id){
        $joinMod = new JoinQueries();
        $classes = $joinMod->getClassesAndTeachersByCourse($id);
        $course=['id_course'=>$classes->res[0]->id_course, 'name'=>$classes->res[0]->course_name, 'date_start'=>$classes->res[0]->date_start, 'date_end'=>$classes->res[0]->date_end];

        return view('details', ['selectedMenu'=>'classesDetails','course'=>$course, 'classes'=>$classes->res]);
    }
    public static function subjectsDetails($id){
        $mod = new Exams();
        $mod->getDistinctByIdClass($id);
        $exams = $mod->data->res;

        $mod = new Works();
        $mod->getDistinctByIdClass($id);
        $works = $mod->data->res;

        $mod = new Percentages();
        $mod->getByIdClass($id);
        $percentages = $mod->data->res;

        $mod = new Classes();
        $mod ->getById($id);
        $courseId = $mod->data->res[0]->id_course;

        $mod = new Courses();
        $mod->getById($courseId);
        $course =$mod->data->res[0];
        $course=['id_course'=>$course->id_course, 'name'=>$course->name, 'date_start'=>$course->date_start, 'date_end'=>$course->date_end];

        return view('details', ['selectedMenu'=>'subjectsDetails', 'course'=>$course, 'exams'=>$exams, 'works'=>$works, 'percentage'=>$percentages]);
    }
}
