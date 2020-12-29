<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>3SCode Academy Manager</title>
    <link href="{{asset('fontawesome/css/all.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/menu.css')}}">
    <link rel="stylesheet" href="{{asset('css/admin.css')}}">
    <link rel="stylesheet" href="{{asset('css/subjects.css')}}">

</head>
<body>

@include('header')
<div class= "nav-bar">
    @include('admin/adminMenu',['selectedMenu'=>$selectedMenu]) {{--Este menú necesita un objeto courses--}}
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
        @include('admin/adminClasses',['classes'=>$classes, 'teachers'=>$teachers, 'courses'=>$courses,])
        @break
        @case('classesSchedule')
        @include('admin/adminClassesSchedule',['freeHoursOfWeek'=>$freeHoursOfWeek, 'formValues'=>$formValues])
        @break
        {{--INSERTAR VISTA BORRAR--}}
        @case('delete')
        @include('admin/adminDelete',['delete_data'=>$teachers_data])
        @break

        {{--DETALLES EN ADMIN--}}
        {{--ESTUDIANTES--}}
        @case('studentsDetails')
        @include('details/studentsDetails', ['course'=>$course, 'students'=>$students])
        @break
        @case('subjectsOfStudent')
        @include('details/subjectsOfStudent', ['course'=>$course, 'subjects'=>$subjects, 'subject?'=>$subjects,])
        @break

        {{--CLASES--}}
        @case('subjects')
        @include('subjects.subjects',['selectedMenu'=>$selectedMenu, 'admin'=>true, 'class_data'=>$class_data, 'user_data'=>$user_data, 'subjects'=>$subjects])
        @break
        @case('subjectsCreate')
        @include('subjects.create', ['$selectedMenu'=>$selectedMenu, 'admin'=>true, 'user_data'=>$user_data, 'class_data'=>$class_data])
        @break
        @case('subjectsUpdate')
        @include('subjects.update', ['$selectedMenu'=>$selectedMenu, 'admin'=>true, 'user_data'=>$user_data, 'class_data'=>$class_data, 'selectedSubjects'=>$selectedSubjects])
        @break

        {{--¿¿¿SIN USO???--}}
        @case('classesDetails')
        @include('details/classesDetails',['course'=>$course,'classes'=>$classes,'percent?'=>$percent])
        @break

        {{--DETALLES ASIGNATURA--}}
        @case('subjectDetails')
        @include('details/subjectDetails',['classes'=>$classes, 'exams'=>$exams, 'works'=>$works, 'percentage'=>$percentage])
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
