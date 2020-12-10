<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Details</title>
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/menu.css')}}">
    <link rel="stylesheet" href="{{asset('css/details.css')}}">
</head>
<body>
@include('header')
<div class= "nav-bar">
    @include('details/detailsMenu',['selectedMenu'=>$selectedMenu,'course'=>$course])
</div>

<div id="main-container">
    @switch($selectedMenu)
        {{--ESTUDIANTES--}}
        @case('studentDetails')
        @include('details/studentsDetails', ['course'=>$course, 'students'=>$students])
        @break
        {{--CLASES--}}
        @case('classesDetails')
        @include('details/classesDetails',['course'=>$course,'classes'=>$classes])
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



