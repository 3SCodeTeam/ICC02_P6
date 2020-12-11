<div class="teacher classes container">
    <div class="teacher classes list container">
        <table class="teacher classes">
            <thead>
            <tr>
                <th class="col color"><p>Color</p></th>
                <th class="col name"><p>Nombre</p></th>
                <th class="col cuorse"><p>Curso</p></th>
                <th class="col status"><p>Estado</p></th>
                <th class="col students"><p>Núm. Alumnos</p></th>
            </tr>
            </thead>
            <tbody>
            @foreach($classes as $c)
            {{--C.name as class_name, c.color, c.id_class, c.id_course, CO.name as course_name, CO.active,
             CO.date_end, CO.date_start, CO.description, count(distinct E.id_student)--}}
            <tr>
                <td class="col color"><div style="background-color: {{$c->color}}">&nbsp;</div></td>
                <td class="col name"><a href="{{'/details/subjects/'.$c->id_class}}"><p>{{$c->class_name}}</p></a></td>
                <td class="col course"><a href="{{'/details/classes/'.$c->id_course}}"><p>{{$c->course_name}}</p></a></td>
                <td class="col status"><p>{{($c->active==1) ? 'Activo' : 'Inactivo'}}</p></td>
                <td class="col students"><p>{{$c->students}}</p></td>
            </tr>
            @endforeach()
            </tbody>
        </table>
    </div>
</div>
