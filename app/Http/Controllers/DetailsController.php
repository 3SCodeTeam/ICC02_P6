<?php


namespace App\Http\Controllers;


use App\Models\Classes;
use App\Models\Courses;
use App\Models\Exams;
use App\Models\JoinQueries;
use App\Models\Percentages;

use App\Models\Works;
use Illuminate\Http\Request;

class DetailsController extends Controller
{
    public static function studentsDetails($id, Request $req){
        $role = $req->session()->get('user_role');
        $mod = new JoinQueries();
        $students = $mod->getStudentsByCourse($id);

        $mod = new Courses();
        $mod->getById($id);
        $course = $mod->data->res[0];
        $course=['role'=>$role, 'id_course'=>$course->id_course, 'name'=>$course->name, 'date_start'=>$course->date_start, 'date_end'=>$course->date_end];

        return view('details', ['selectedMenu'=>'studentsDetails', 'course'=>$course,'students'=>$students->res]);
    }
    public static function classesDetails($id, Request $req){
        $role = $req->input('user_role');
        $joinMod = new JoinQueries();
        $classes = $joinMod->getClassesAndTeachersByCourse($id);

        $course= self::getCourseData($id, $role);

        return view('details', ['selectedMenu'=>'classesDetails','course'=>$course, 'classes'=>$classes->res]);
    }
    public static function subjectsDetails($id, Request $req){
        $role = $req->input('user_role');
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
        $course = self::getCourseData($courseId, $role);

        return view('details', ['role'=>$role, 'classes' => $mod->data->len, 'selectedMenu'=>'subjectsDetails', 'course'=>$course, 'exams'=>$exams, 'works'=>$works, 'percentage'=>$percentages]);
    }
    private static function getCourseData($courseId, $role){
        $mod = new Courses();
        $mod->getById($courseId);
        $course =$mod->data->res[0];
        $course=['role'=>$role, 'id_course'=>$course->id_course, 'name'=>$course->name, 'date_start'=>$course->date_start, 'date_end'=>$course->date_end];
        return $course;
    }
}
