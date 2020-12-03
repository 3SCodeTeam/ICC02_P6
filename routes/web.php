<?php

use App\Models\Classes;
use App\Models\Enrollments;
use App\Models\JoinQueries;
use App\Models\Schedules;
use App\Models\Students;
use App\Models\Teachers;
use App\Models\UsersAdmin;
use Illuminate\Support\Facades\Route;

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
Route::get('/{type}', function($type){
    switch ($type){
        case 'JoinQueries': $c = new JoinQueries(); $c->getByDOW(); dd($c);
        case 'Classes': $c = new Classes(); break;
        case 'Courses': $c = new Courses(); break;
        case 'Schedules': $c = new Schedules(); break;
        case 'Enrollments': $c = new Enrollments(); break;
        case 'Students': $c = new Students(); break;
        case 'Teachers': $c = new teachers(); break;
        case 'Admin': $c = new UsersAdmin();break;
    }
    $c->getAll();
    dd($c);
});
