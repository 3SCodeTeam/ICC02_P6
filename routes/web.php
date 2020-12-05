<?php

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
Route::get('/signin', function (){
    return view('signin',);
});

Route::match(array('GET', 'POST'),'/{controller}/{method}', function ($controller, $method, Request $request){


});

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
