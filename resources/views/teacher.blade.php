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
    <link rel="stylesheet" href="{{asset('css/teacher.css')}}">
</head>
<body>

@include('header')
<div class= "nav-bar">
    @include('teacher.menu',['selectedMenu'=>$selectedMenu, 'id_class ??'=>$id_class ?? ''])
</div>

<div id="main-container">

    <H3>Profesor: {{$user_data['name'].' '.$user_data['surname']}}</H3>

    <div id="main-container">
    @switch($selectedMenu)
        {{--INSERTAR VISTA PERFIL--}}
        @case('profile')
        @include('teacher.profile', ['user_data'=>$user_data])
        @break
        {{--INSERTAR VISTA CLASES--}}
        @case('classes')
        @include('teacher.classes',['classes'=>$classes,'user_data'=>$user_data])
        @break
        @case('students')
        @include('teacher.classDetails',['$selectedMenu'=>$selectedMenu, 'students'=>$students,'marks'=>$marks])
        @break
        @case('subjects')
        @include('teacher.classDetails',['$selectedMenu'=>$selectedMenu, 'class_data'=>$class_data, 'subjects'=>$subjects])
        @break
        @case('studentDetails')
        @include('teacher.studentDetails', ['$selectedMenu'=>$selectedMenu, 'user_data'=>$user_data, 'student'=>$student, 'exams'=>$exams, 'works'=>$works])
        @break
    @endswitch
</div>

{{--TODO: CSS alert-msg--}}
    <div class="alert-msg">
        @isset($msg)
            <div class="msg">{{$msg}}</div>
        @endisset
    </div>

@include('footer')

</body>
</html>
