<div class="teachers classDetail container">
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
        <div class="teachers classDetails subjects">
            <div class="teachers classDetails create">
                <form action="{{asset('/teachers/subjectsPost/'.$id_class)}}" method="post" id="newSubject" >
                    @csrf
                    <div class="teacher classDetail inputs right">
                        <label for="name">Nombre</label>
                        <input id="name" name="name" type="text" placeholder="Nombre del trabajo" required />
                        <label for="date">Fecha de entrega</label>
                        <input id="date" name="date" type="date" min="{{date('Y-m-d')}}" max="{{$class_data->date_end}}" />
                        <label for="time">Hora de entrega</label>
                        <input id="time" name="time" type="time"/>
                        <div class="teacher classDetail radio">
                            <input type="radio" id="work" name="type" value="works" />
                            <label for="work">Trabajo</label>
                            <input type="radio" id="exam" name="type" value="exams" />
                            <label for="exam">Exámen</label>
                        </div>
                    </div>
                    <div class="teacher classDetail inputs left">
                        <label for="description" >Descripción</label>
                        <textarea name="description" id="description" placeholder="Descripción..."></textarea>
                    </div>
                    <div class="teacher classDetail inputs down">
                        <input type="submit" value="Crear" />
                    </div>
                </form>
            </div>
            @if(count($subjects['works'])>0 || count($subjects['exams'])>0)
            <form action="{{asset('/teachers/subjectsPost/'.$id_class)}}" name="subjects" id="subjects" method="post">
                @csrf
                <div class="teachers classDetails exams">
                    @if(count($subjects['exams'])>0)
                        <table class="teachers classDetails exams table">
                            <thead>
                                <tr class="row header">
                                    <th class="col selector"><span class="checkbox"> </span></th>
                                    <th class = "col name"><p>Título</p></th>
                                    <th class = "col description"><p>Descripción</p></th>
                                    <th class = "col date"><p>Fecha de entrega</p></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subjects['exams'] as $e)
                                    <tr class="row data">
                                        <td class="col selector"><span class="checkbox"><input type="checkbox" value='exam' name="{{$e->name}}"></span></td>
                                        <td class="col name"><p><{{$e->name}}></p></td>
                                        <td class="col description"><p>{{$e->description}}</p></td>
                                        <td class="col date"><p>{{$e->deadline}}</p></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <div class="teachers classDetails works">
                    @if(count($subjects['works'])>0)
                    <table class="teachers classDetails works table">
                        <thead>
                        <tr class="row header">
                            <th class="col selector"><span class="checkbox"> </span></th>
                            <th class = "col name"><p>Título</p></th>
                            <th class = "col description"><p>Descripción</p></th>
                            <th class = "col date"><p>Fecha de entrega</p></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($subjects['works'] as $w)
                            <tr class="row data">
                                <td class="col selector"><span class="checkbox"> <input type="checkbox" value='work' name="{{$w->name}}"></span></td>
                                <td class="col name"><p><{{$w->name}}></p></td>
                                <td class="col description"><p>{{$w->description}}</p></td>
                                <td class="col date"><p>{{$w->deadline}}</p></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
                <div class="submit">
                    <label for="action">Acción:</label>
                    <select name="action" id="action">
                        <option value="delete">Borrar</option>
                        <option value="update" selected>Actualizar</option>
                    </select>
                    <div class="teacher classDetails submit">
                        <input type="submit" value="Ejecutar">
                    </div>
                </div>
            </form>
            @endif
        </div>
    @endif
</div>
