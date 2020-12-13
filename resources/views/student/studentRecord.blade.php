<div class="student record container">

    {{--INFORMACIÖN DE LA ASIGNATURA--}}
    <div class="student record info container">
        <h3 class="student record title">{{$course->name}}</h3>
        <div class="student record data container">
            <p>Período del curso: <span>{{$course->date_start}} / {{$course->date_end}}</span></p>
            <p>Descripción:</p>
            <p>{{$course->description}}</p>
        </div>
    </div>


    <div class="student record list container">
        <table>
            <thead>
            <tr class="row header">
                <th class="col subject">Asignatura</th>
                <th class="col teacher">Profesor</th>
                <th class="col workWeight">Peso evaluación cont.</th>
                <th class="col work">Nota trabajos</th>
                <th class="col exam">Nota exámenes</th>
                <th class="col global">Nota final</th>
            </tr>

            </thead>
            <tbody>
            {{--$marks[$c->id_class] = ['exam'=>$examsMarks, 'work'=>$worksMarks, 'global'=>$global, 'weights'=>$weights]--}}
            {{--['exam'=>$eWeight, 'works'=>$wWeight]--}}
            @foreach($classes as $c)
                <tr class="row class {{!($marks[$c->id_class]['global']==='----') ? 'finished' : 'notFinished'}}">
                    <td class="col subject"><a href="{{asset('student/classDetails/'.$c->id_class)}}" ><p>{{$c->class_name}}</p></a></td>
                    <td class="col teacher"><p>{{$c->teacher_name.' '.$c->surname}} <span>(<a href="mailto:{{$c->email}}">{{$c->email}}</a>)</span></p></td>
                    <td class="col workWeight"><p>{{$marks[$c->id_class]['weights']['works']*100}}%</p></td>
                    <td class="col work"><p>{{$marks[$c->id_class]['work']}}</p></td>
                    <td class="col exam"><p>{{$marks[$c->id_class]['exam']}}</p></td>
                    <td class="col global"><p>{{$marks[$c->id_class]['global']}}</p></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
