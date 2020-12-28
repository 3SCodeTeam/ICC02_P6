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
                                <div class="cell class_name">{{$c->class_name}}</div>
                            </a>
                        </td>
                        <td class="continuous_assessment">
                                @if(isset($percent) && ($percent == $c->id_class))
                                    <form action="{{asset('/details/percentPost')}}" method="post" name="percent" id="percent">
                                        @csrf
                                        <input type="text" name="id_class" hidden value="{{$percent}}"/>
                                        <input type="text" name="type" hidden value="continuous_assessment"/>
                                    <div class="cell continuous_assessment">
                                        <input type="number" name="continuous_assessment" min="30" max="90" step="5" value="{{$c->continuous_assessment*100}}"/> %
                                        <input type="submit" value="OK">
                                    </div>
                                    </form>
                                @else
                                    <a href="{{asset('details/classes/'.$course['id_course'].'/'.$c->id_class)}}">
                                        <div class="cell continuous_assessment">{{$c->continuous_assessment*100}}%</div>
                                    </a>
                                @endif
                        </td>
                        <td class="exams">
                            <a href="{{asset('details/classes/'.$course['id_course'].'/'.$c->id_class)}}">
                                <div class="cell exam">{{$c->exams*100}}%</div>
                            </a>
                        </td>
                        <td class="teacher_name"><div class="cell teacher_name">{{$c->surname.', '.$c->teacher_name}}</div></td>
                        <td class="teacher_email"><div class="cell teacher_email"><a href="mailto:{{$c->email}}">{{$c->email}}</a></div></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
