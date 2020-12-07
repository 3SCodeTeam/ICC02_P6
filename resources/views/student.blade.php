<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>3SCode Academy Manager</title>
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/menu.css')}}">
    <link rel="stylesheet" href="{{asset('css/student.css')}}">
</head>
<body>
    @include('header')
    <div class= "nav-bar">
        @include('student/studentMenu',['selectedMenu'=>$selectedMenu])
    </div>

    <div id="main-container">
        @switch($selectedMenu)
            {{--INSERTAR VISTA PERFIL--}}
            @case('profile')
                @include('student/studentProfile', ['user_data'=>$user_data, 'selectedMenu'=>$selectedMenu])
            @break
            {{--INSERTAR VISTA HORARIO--}}
            @case('mSchedule')
            @case('wSchedule')
            @case('dSchedule')
                @include('student/studentSchedule', ['schedule_data'=>$schedule_data, 'selectedMenu'=>$selectedMenu])
            @break
            {{--INSERTAR VISTA MATRÍCULA--}}
            @case('enrollment')
                @include('student/studentEnrollment', ['courses_data'=>$courses_data, 'studentCourses'=>$studentCourses])
            @break
        @endswitch
    </div>

    <div class="alert-msg">
    @isset($msg)
        <div class="msg">{{$msg}}</div>
    @endisset
    </div>
    @include('footer');
</body>
</html>
