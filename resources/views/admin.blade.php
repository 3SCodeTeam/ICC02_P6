<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>3SCode Academy Manager</title>
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/menu.css')}}">
    <link rel="stylesheet" href="{{asset('css/admin.css')}}">
</head>
<body>

@include('header')
<div class= "nav-bar">
    @include('admin/adminMenu',['selectedMenu'=>$selectedMenu])
</div>

<div id="main-container">
    @switch($selectedMenu)
        {{--INSERTAR VISTA PERFIL--}}
        @case('profile')
        @include('admin/adminProfile', ['user_data'=>$user_data])
        @break
        {{--INSERTAR VISTA PROFESOR--}}
        @case('teachers')
        @include('admin/adminTeachers',['teachers_data'=>$teachers_data])
        @break
        {{--INSERTAR VISTA CURSOS--}}
        @case('courses')
        @include('admin/adminCourses',['courses_data'=>$courses_data])
        @break
        {{--INSERTAR VISTA CLASES--}}
        @case('classes')
        @include('admin/adminClasses',['classes_data'=>$classes_data])
        @break
        {{--INSERTAR VISTA BORRAR--}}
        @case('delete')
        @include('admin/adminDelete',['delete_data'=>$teachers_data])
        @break
    @endswitch
</div>

<div class="alert-msg">
    @isset($msg)
        <div class="msg">{{$msg}}</div>
    @endisset
</div>

@include('footer')
</body>
</html>
