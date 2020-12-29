<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DetailsController;
use App\Http\Controllers\LogInController;
use App\Http\Controllers\SignInController;
use App\Http\Controllers\StudentController;

use App\Http\Controllers\Subjects;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function(){
    $controller = request('controller');
    $method = request('method');
    return view('welcome', ['controller'=>$controller, 'method'=> $method]);
    });
*/
//Willcards

Route::get('/no-auth',function (){
    return view('login',['msg'=>'Debes iniciar sesión.']);
})->name('login');

Route::get('/', function (){
    return view('login');
});

Route::get('/{key}', function ($key){
    if($key === 'signin'){return view('signin');}
    return view('login');
});

//LOGIN ROUTE
Route::match(array('GET', 'POST'),'/login/{method}', function ($method, Request $request){
    switch ($method){
        case 'end': return LogInController::end();
        case 'new': return LogInController::new();
        case 'post': return LogInController::post($request);
    }
    return LogInController::error('La ruta solicitada no es accesible.');
});

//SIGNIN ROUTE
Route::match(array('GET', 'POST'),'/signin/{method}', function ($method, Request $request){
    switch ($method){
        case 'post': return SignInController::post($request);
        case 'new': return view('signin');
    }
    return SignInController::error('La ruta solicitada no es accesible.');
});

//SESION CONTROL FROM THIS POINT
Route::get('/student/{method}/{id?}', function (Request $req, $method, $id=null){
    switch ($method){
        case 'start': return StudentController::start($req);
        case 'profile': return StudentController::profile($req);
        case 'enrollment': return StudentController::enrollment($req);
        case 'mSchedule': return StudentController::mSchedule($req);
        case 'wSchedule': return StudentController::wSchedule($req);
        case 'dSchedule': return StudentController::dSchedule($req);
        case 'record': return StudentController::record($req);
        case 'classDetails':return StudentController::recordDetail($id, $req);
    }
    return LogInController::error('Recurso no disponible');
})->middleware('Session')->name('student');

Route::post('/student/{method}', function ($method, Request $request){
    switch ($method){
        case 'enrollmentPost': return StudentController::enrollmentPost($request);
        case 'profilePost': return StudentController::profilePost($request);
        //TODO: horario día concreto
        //TODO: horario semana concreta
        //TODO: horario mes concreto
    }
    return LogInController::error('Recurso no disponible');
})->middleware('Session');

Route::get('/admin/{method}/{param1?}', function (Request $req, $method, $param1=null){
    switch ($method){
        case 'start': return AdminController::start($req);
        case 'profile': return AdminController::profile($req);
        case 'teachers': return AdminController::teacher();
        case 'courses': return AdminController::courses();
        case 'classes': return AdminController::classes();
        case 'delete': return AdminController::delete();
        case 'courseActive':  return AdminController::courseActive($param1);
        }
    return LogInController::error('Recurso no disponible');
})->middleware('Session:admin')->name('admin');

Route::post('/admin/{method}', [function ($method, Request $req){
    switch ($method){
        case 'profilePost': return AdminController::profilePost($req);
        case 'teachersPost': return AdminController::teachersPost($req);
        case 'coursesPost': return AdminController::coursesPost($req);
        case 'classesPost': return AdminController::classesPost($req);
        case 'classesPostSchedule': return AdminController::classesPostSchedule($req);
        case 'deletePost': return AdminController::deletePost();
    }
    return LogInController::error('Recurso no disponible');
}])->middleware('Session:admin');//->name('admin');

Route::match(array('GET', 'POST'),'details/{method}/{param1?}/{param2?}/{param3?}', function (Request $req, $method, $param1=null, $param2=null, $param3=null){
    switch ($method){
        case 'students': return DetailsController::studentsDetails($param1,$req);
        case 'classes': return DetailsController::classesDetails($param1, $param2, $param3);
        case 'percentPost': return DetailsController::percentPost($req);
        case 'subjects': return DetailsController::subjectsDetails($param1,$req);
        case 'subjectsOfStudent': return DetailsController::subjectsOfStudent($param1, $param2);
        case 'records': return DetailsController::record($param1, $param2);
        case 'recordPost': return DetailsController::recordPost($req);
    }
    return LogInController::error('Recurso no disponible');


})->middleware('Session:admin');

Route::match(array('GET', 'POST'), 'teachers/{method}/{class?}/{student?}', function (Request $req, $method, $class=null,$student=null){
    switch ($method){
        case 'start': return TeacherController::start($req);
        case 'profile': return TeacherController::profile($req);
        case 'profilePost': return TeacherController::profilePost($req);
        case 'classes': return TeacherController::classes($req);
        case 'students': return TeacherController::students($req, $class);
        //case 'subjects': return TeacherController::subjects($req, $class);
        case 'studentDetails': return  TeacherController::studentDetails($req, $class, $student);
       //case 'subjectsPost': return  TeacherController::subjectsPost($req,$class);
    }
    return LogInController::error('Recurso no disponible');
})->middleware('Session:teacher')->name('teacher');

Route::match(array('GET', 'POST'),'subjects/{method}/{class?}/{student?}', function (Request $req, $method, $class=null,$student=null) {
    switch ($method) {
        case 'create': return Subjects::create($req, $class);
        case 'subjectsPost': return  Subjects::subjectsPost($req, $class);
        case 'subjectsMarks': return Subjects::subjectMarks($req, $class, $student);
        case 'update': return Subjects::update($req, $class);
        case 'updatePost': return Subjects::updatePost($req, $class);
        case 'subjects': return Subjects::subjects($req, $class);
    }
    return LogInController::error('Recurso no disponible');
})->middleware('Session:teacher');
