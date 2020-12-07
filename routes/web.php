<?php

use App\Http\Controllers\LogInController;
use App\Http\Controllers\SignInController;
use App\Http\Controllers\StudentController;

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
        case 'end': return LogInController::end();
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
            return SignInController::error('La ruta solicitada no es accesible.');
    }
});

Route::get('/student/{method}', function ($method, Request $req){
    switch ($method){
        case 'start': return StudentController::start($req);
        case 'profile': return StudentController::profile($req);
        case 'enrollment': return StudentController::enrollment($req);
        case 'schedule': return StudentController::schedule();
        case 'wSchedule': return StudentController::wSchedule();
        case 'dSchedule': return StudentController::dSchedule();
        case 'record': return StudentController::record();
        default:
            return LogInController::error('Recurso no deisponible');
    }
});
Route::post('/student/{method}', function ($method, Request $request){
    switch ($method){
        case 'enrollmentPost': return StudentController::enrollmentPost($request);
        case 'profilePost': return StudentController::profilePost($request);
        case 'recordDetail':return StudentController::recordDetail($request);
        //TODO: horario día concreto
        //TODO: horario semana concreta
        //TODO: horario mes concreto
        default:
            return LogInController::error('Recurso no disponible');
    }
});
