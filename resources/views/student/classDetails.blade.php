<div class="student classDetails container">

    {{--INFORMACIÖN DE LA ASIGNATURA--}}
    <div class="student recordDetails info container">
        <h3 class="student recordDetails title">{{$data->class_name.' ('.$data->course_name.')'}}</h3>
        <div class="student recordDeteails data">
            <p>Curso: <span>{{$data->course_name}}</span></p>
            <p>Profesor: <span>{{$data->teacher_name}}</span><span><a href="mailto:{{$data->email}}">({{$data->email}})</a></span></p>
            <p>Evaluación continua:<span>{{$data->works*100}}%</span> Examen: <span>{{$data->exams*100}}%</span></p>
        </div>
    </div>

    {{--LISTADO DE TRABAJOS--}}
    <div class="student classDetails container works">
        @foreach($works as $w)
            <div class="student classDetails work">
                <span>{{$w->name}}</span>
                @if(isset($w->mark))
                <span>{{($w->mark!=-1) ? $w->mark : '---'}}</span>
                @else
                    <span>----</span>
                @endif
            </div>
        @endforeach
    </div>

    {{--LISTADO DE EXÁMENES--}}
    <div class="student classDetails container exams">
        @foreach($exams as $e)
            <div class="student classDetails exam">
                <span>{{$e->name}}</span>
                @if(isset($e->mark))
                    <span>{{($e->mark!=-1) ? $e->mark : '---'}}</span>
                @else
                    <span>----</span>
                @endif
            </div>
        @endforeach
    </div>
</div>
