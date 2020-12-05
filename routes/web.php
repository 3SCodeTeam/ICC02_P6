<?php

use App\Http\Controllers\LogInController;
use App\Http\Controllers\SignInController;
use App\Models\Classes;
use App\Models\Courses;
use App\Models\Enrollments;
use App\Models\JoinQueries;
use App\Models\Notifications;
use App\Models\Schedules;
use App\Models\Students;
use App\Models\Teachers;
use App\Models\UsersAdmin;
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

Route::get('/', function (){
    return view('login',);
});

Route::get('/{key}', function ($key){
    if($key === 'signin'){return view('signin');}
    return view('login');
});

//LOGIN ROUTE
Route::match(array('GET', 'POST'),'/login/{method}', function ($method, Request $request){
    switch ($method){
        case 'end': LogInController::end();
        case 'new': return LogInController::new();
        case 'post': return LogInController::post($request);
        default:
            return LogInController::error('La ruta solicitada no es accesible.');
    }
});

//SIGNIN ROUTE
Route::match(array('GET', 'POST'),'/signin/{method}', function ($method, Request $request){
    switch ($method){
        case 'post': return SignInController::post($request);
        case 'new': return view('signin');
        default:
            SignInController::error('La ruta solicitada no es accesible.');
    }
});

//RUTAS DE TESTEO
Route::match(array('GET', 'POST'),'/{type}', function($type, Request $request){
    switch ($type){
        case 'JoinQueries': $c = new JoinQueries(); $c->getByDOW(); dd($c);
        case 'Classes': $c = new Classes(); break;
        case 'Courses': $c = new Courses(); break;
        case 'Schedules': $c = new Schedules(); break;
        case 'Enrollments': $c = new Enrollments(); break;
        case 'Students': $c = new Students(); break;
        case 'Teachers': $c = new teachers(); break;
        case 'Admin': $c = new UsersAdmin();break;
        case 'Notifications': $c = new Notifications();break;
        case 'Percentage': $c = new \App\Models\Percentages();break;
        case 'Works': $c = new \App\Models\Works(); break;
        case 'Exams': $c = new \App\Models\Exams(); break;
        default: return $c = 'ERROR';
    }
    dd($request->input('nombre'));
    $c->getAll();
    dd($c);
});
