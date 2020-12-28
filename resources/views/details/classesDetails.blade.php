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
                            @if(($percent['type'] == 'name') && ($percent['id_class'] == $c->id_class))
                                <form action="{{asset('/details/percentPost')}}" method="post" name="class_name" id="class_name">
                                    @csrf
                                    <input type="hidden" name="id_class" value="{{$percent['id_class']}}"/>
                                    <input type="hidden" name="type" value="name"/>
                                    <div class="cell name">
                                        <input type="text" id="name" name="name" value="{{$c->class_name}}" autofocus/>
                                        <input type="submit" value="OK">
                                    </div>
                                </form>
                            @else
                                <a class="text" href="{{asset('subjects/subjects/'.$c->id_class)}}">
                                    <div class="cell class_name">{{$c->class_name}}</div>
                                </a>
                                <a class="button" href="{{asset('details/classes/'.$course["id_course"].'/'.$c->id_class.'/name')}}">
                                    <button type="button">Editar</button>
                                </a>
                            @endif
                        </td>
                        <td class="continuous_assessment">
                                @if(($percent['type'] =='continuous_assessment') && ($percent['id_class'] == $c->id_class))
                                    <form action="{{asset('/details/percentPost')}}" method="post" name="percent" id="percent">
                                        @csrf
                                        <input type="hidden" name="id_class" value="{{$percent['id_class']}}"/>
                                        <input type="hidden" name="type" value="continuous_assessment"/>
                                    <div class="cell continuous_assessment">
                                        <input type="number" id="continuous_assessment" name="continuous_assessment" min="30" max="90" step="5" value="{{$c->continuous_assessment*100}}"/> %
                                        <input type="submit" value="OK">
                                    </div>
                                    </form>
                                @else
                                    <a href="{{asset('details/classes/'.$course["id_course"].'/'.$c->id_class.'/continuous_assessment')}}">
                                        <div class="cell continuous_assessment">{{$c->continuous_assessment*100}}%</div>
                                    </a>
                                @endif
                        </td>
                        <td class="exams">
                            @if(($percent['type'] =='exams') && ($percent['id_class'] == $c->id_class))
                                <form action="{{asset('/details/percentPost')}}" method="post" name="percent" id="percent">
                                    @csrf
                                    <input type="hidden" name="id_class" value="{{$percent['id_class']}}"/>
                                    <input type="hidden" name="type" value="exams"/>
                                    <div class="cell exams">
                                        <input type="number" id="exams" name="exams" min="10" max="70" step="5" value="{{$c->exams*100}}"/> %
                                        <input type="submit" value="OK">
                                    </div>
                                </form>
                            @else
                            <a href="{{asset('details/classes/'.$course["id_course"].'/'.$c->id_class.'/exams')}}">
                                <div class="cell exams">{{$c->exams*100}}%</div>
                            </a>
                            @endif
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
