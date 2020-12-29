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

    <div class="classDetails marks">
        {{--LISTADO DE TRABAJOS--}}
        <div class="classDetails works table">
            <h3>Trabajos</h3>
            <table>
                <thead>
                <tr class="row header">
                    <th class="col name"><div class="cell name">Trabajo</div></th>
                    <th class="col mark"><div class="cell mark">Nota</div></th>
                </tr>
                </thead>
                <tbody>
                @foreach($works as $w)
                    <tr class="{{($w->mark < 0) ? 'notFinished' : (($w->mark < 5) ? 'fail':'finished')}}">
                        <td class="col name"><div class="cell name">{{$w->name}}</div></td>
                        <td class="col mark">
                            <div class="cell mark">{{($w->mark!=-1) ? $w->mark : ''}}</div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{--LISTADO DE EXÁMENES--}}
        <div class="classDetails exams table">
            <h3>Exámenes</h3>
            <table>
                <thead>
                <tr class="row header">
                    <th class="col name"><div class="cell name">Examen</div></th>
                    <th class="col mark"><div class="cell mark">Nota</div></th>
                </tr>
                </thead>
                <tbody>
                @foreach($exams as $e)
                    <tr class="{{($e->mark < 0) ? 'notFinished' : (($e->mark < 5) ? 'fail':'finished')}}">
                        <td class="col name"><div class="cell name">{{$e->name}}</div></td>
                        <td class="col mark">
                            <div class="cell mark">{{($e->mark!=-1) ? $e->mark : ''}}</div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
