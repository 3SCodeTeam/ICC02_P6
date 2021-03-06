<div class="classes main container">

    {{--FORMULARIO PARA LA CREACIÓN DE UNA ASIGNATURA--}}

    <div class="classes form container">
        <h2>Nueva asignatura</h2>
        <form action="{{asset('admin/classesPost')}}" method="Post" id="form_classes1" name="form_classes1">
            <div class="form left container">
                <label for="teachers">Profesor:</label><br>
                <select name="teacher" form="form_classes1" id="teachers">
                    @foreach($teachers as $t)
                        <option value="{{$t->id_teacher}}">{{$t->email}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form right container">
                <label for="courses">Curso:</label><br>
                <select name="course" form="form_classes1" id="courses">
                    @foreach($courses as $c)
                        <option value="{{$c->id_course}}">{{$c->name}}</option>
                    @endforeach
                </select>
            </div>
            <br><input class="input button" type="submit" value="Crear">
            @csrf
        </form>
    </div>

    {{--LISTADO DE ASIGNATURAS CREADAS--}}
    @if(count($classes)>0)
        <div class="classes list container">
            <table><tbody>
                <tr class="row header">
                    <th class="col action"><i class="fas fa-trash"></i></th>
                    <th class="col name"><p>Asignatura</p></th>
                    <th class="col course"><p>Curso</p></th>
                    <th class="col teacher"><p>Profesor</p></th>
                    <th class="col color"><p>Color</p></th>
                </tr>
                @foreach($classes as $c)
                    <tr>
                        <td class="col action">
                            <div class="cell action">
                                <a href="{{asset('/admin/deleteClass/'.$c->id_class)}}">
                                    <button type="button"><i class="fas fa-trash"></i></button>
                                </a>
                            </div>
                        </td>
                        <td class="col name"><p><a href="{{asset('/subjects/subjects/'.$c->id_class)}}">{{$c->class_name}}</a></p></td>
                        <td class="col course"><p><a href="{{asset('/details/classes/'.$c->id_course)}}">{{$c->course_name}}</a></p></td>
                        <td class="col teacher"><p><a href="{{asset('/details/teachers/'.$c->id_teacher)}}">{{$c->surname.', '.$c->teacher_name}}</a><a href="mailto:{{$c->email}}">{{' ('.$c->email.')'}}</a></p></td>
                        <td class="col color"><div class="color container" style="background-color: {{$c->color}}"> </div></td>
                    </tr>
                @endforeach
            </tbody></table>
        </div>
    @endif
</div>
{{--TODO: CSS de esta vista--}}
