<div class="teachers classDetails container">
    @if($selectedMenu =='students')
        <div class="teachers classDetails students">
            <table>
                <thead>
                <tr class="row header">
                    <th class="col name"><p>Nombre y apellidos</p></th>
                    <th class="col email"><p>Email</p></th>
                    <th class="col phone"><p>Teléfono</p></th>
                    <th class="col work"><p>Nota Eva.Cont.</p></th>
                    <th class="col work"><p>Nota Exámenes</p></th>
                    <th class="col work"><p>Nota Final</p></th>
                </tr>
                </thead>
                <tbody>
                @foreach($students as $s)
                    <tr class="row data">
                            <td class="col name">
                                <a href="{{'/teachers/studentDetails/'.$s->id_class.'/'.$s->id_student}}">
                                    <p>{{$s->student_surname}}, {{$s->student_name}}</p>
                                </a>
                            </td>
                            <td class="col email">
                                <a href="mailto:{{$s->student_email}}">
                                    <p>{{$s->student_email}}</p>
                                </a>
                            </td>
                            <td class="col phone"><p>{{$s->student_telephone}}</p></td>
                            <td class="col work"><p>{{$marks[$s->id_student]['work']}}</p></td>
                            <td class="col exam"><p>{{$marks[$s->id_student]['exam']}}</p></td>
                            <td class="col global"><p>{{$marks[$s->id_student]['global']}}</p></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
    @if($selectedMenu =='subjects')
        @if(count($subjects['works'])>0 || count($subjects['exams'])>0)
        <form action="{{asset('/teachers/subjectsPost/'.$id_class)}}" name="subjects" id="subjects" method="post">
            <div class="teachers classDetails subjects submit">
                {{--<label for="action">Acción:</label>--}}
                <select name="action" id="action">
                    <option value="delete">Borrar</option>
                    <option value="update" selected>Actualizar</option>
                </select>
                <div class="teacher classDetails submit">
                    <input type="submit" value="Ejecutar">
                </div>
                <div class="teachers classDetails subjects newSubject">
                    <a href="{{asset('/subjects/create/'.$id_class)}}"><button type="button">Crear nuevo</button></a>
                </div>
            </div>
            @csrf
            <div class="teachers classDetails subjects exams">
                @if(count($subjects['exams'])>0)
                    <h3>Exámenes</h3>
                    <table class="teachers classDetails subjects exams table">
                        <thead>
                            <tr class="row header">
                                <th class="col selector"><span class="checkbox"> </span></th>
                                <th class = "col name"><p>Nombre</p></th>
                                <th class = "col date"><p>Fecha de entrega</p></th>
                                <th class = "col description"><p>Descripción</p></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subjects['exams'] as $e)
                                <tr class="row data">
                                    <td class="col selector"><div class="cell checkbox"><input type="checkbox" value='exam' name="{{$e->name}}" id="{{$e->name}}"></div></td>
                                    <td class="col name"><label for="{{$e->name}}"><div class="cell">{{$e->name}}</div></label></td>
                                    <td class="col date"><div class="cell">{{$e->deadline}}</div></td>
                                    <td class="col description"><div class="cell">{{$e->description}}</div></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <div class="teachers classDetails subjects works">
                @if(count($subjects['works'])>0)
                    <h3>Trabajos</h3>
                <table class="teachers classDetails subjects works table">
                    <thead>
                    <tr class="row header">
                        <th class="col selector"><span class="checkbox"> </span></th>
                        <th class = "col name"><p>Nombre</p></th>
                        <th class = "col date"><p>Fecha de entrega</p></th>
                        <th class = "col description"><p>Descripción</p></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($subjects['works'] as $w)
                        <tr class="row data">
                            <td class="col selector"><div class="cell checkbox"> <input type="checkbox" value='work' name="{{$w->name}}" id="{{$w->name}}"></div></td>
                            <td class="col name"><label for="{{$w->name}}"><div class="cell">{{$w->name}}</div></label></td>
                            <td class="col date"><div class="cell">{{$w->deadline}}</div></td>
                            <td class="col description"><div class="cell">{{$w->description}}</div></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </form>
        @endif
    @endif
</div>
