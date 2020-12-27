<div class="main container classes details">
    <h2>{{$course['name']}}</h2>
    <div class="container classes">
        <h3>Asignaturas</h3>
        <div class="admin classes table container">
            <table>
                <thead>
                <tr>
                    <th class="class_name">Asignatura</th>
                    <th class="continuous_assessment">Peso Ev.cont.</th>
                    <th class="exams">Peso exámenes</th>
                    <th class="teacher_name">Profesor</th>
                    <th class="teacher_email">Email</th>
                </tr>
                </thead>
                <tbody>
                @foreach($classes as $c)
                    <tr>
                        <td class="class_name">
                            <a href="{{asset('subjects/subjects/'.$c->id_class)}}">
                                <div class="cell">{{$c->class_name}}</div>
                            </a>
                        </td>
                        <td class="continuous_assessment">
                            <a href="{{asset('details/classesUpdate/'.$c->id_class)}}">
                                <div class="cell">{{$c->continuous_assessment*100}}%</div>
                            </a>
                        </td>
                        <td class="exams">
                            <a href="{{asset('details/classesUpdate/'.$c->id_class)}}">
                                <div class="cell">{{$c->exams*100}}%</div>
                            </a>
                        </td>
                        <td class="teacher_name"><div class="cell">{{$c->surname.', '.$c->teacher_name}}</div></td>
                        <td class="teacher_email"><div class="cell"><a href="mailto:{{$c->email}}">{{$c->email}}</a></div></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
