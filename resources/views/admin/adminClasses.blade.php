<div class="main classes container">

    {{--FORMULARIO PARA LA CREACIÓN DE UNA ASIGNATURA--}}

    <div class="form class container">
        <h2>Nueva asignatura</h2>
        <form action="{{asset('admin/classesPost')}}" method="Post" id="form_classes1" name="form_classes1">
            @csrf
            <div class="form left container">
                <label for="teachers">Profesor:</label>
                <select name="teacher" form="form_classes1" id="teachers">
                    @foreach($teachers as $t)
                        <option value="{{$t->id_teacher}}">{{$t->email}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form right container">
                <label for="courses">Cursos:</label>
                <select name="course" form="form_classes1" id="courses">
                    @foreach($courses as $c)
                        <option value="{{$c->id_course}}">{{$c->name}}</option>
                    @endforeach
                </select>
            </div>
            <input type="submit" value="Crear">
        </form>
    </div>

    {{--LISTADO DE ASIGNATURAS CREADAS--}}
    @if(count($classes)>0)
        <div class="list class container">
            <table><tbody>
                <tr class="row header">
                    <th class="col name"><p>Asignatura</p></th>
                    <th class="col color"><p>Color</p></th>
                    <th class="col curse"><p>Curso</p></th>
                    <th class="col teacher"><p>Profesor</p></th>
                </tr>
                @foreach($classes as $c)
                    <tr>
                        <td class="col name"><p>{{$c->class_name}}</p></td>
                        <td class="col color" style="color: {{$c->color}}"></td>
                        <td class="col course"><p><a href="{{asset('admin/courses/'.$c->id_course)}}">{{$c->course_name}}</a></p></td>
                        <td class="col teacher"><p><a href="{{asset('admin/teachers/'.$c->id_teacher)}}">{{$c->surname.', '.$c->teacher_name}}</a><a href="mailto:{{$c->email}}">{{' ('.$c->email.')'}}</a></p></td>
                    </tr>
                @endforeach
            </tbody></table>
        </div>
    @endif
</div>
{{--TODO: CSS de esta vista--}}
