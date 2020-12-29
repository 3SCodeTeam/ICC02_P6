<div class="teachers classDetails container">
    @if($selectedMenu =='students')
        <h3>{{$class_data->class_name}} (<a href="{{asset('/teachers/students/'.$class_data->id_class)}}"><span>{{$course['name']}}</span></a>)</h3>
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
                            <td class="col work">
                                <p>{{($marks[$s->id_student]['work'] === '----') ? '' : number_format($marks[$s->id_student]['work'],2,',','')}}</p>
                            </td>
                            <td class="col exam">
                                <p>{{($marks[$s->id_student]['exam'] === '----') ? '' : number_format($marks[$s->id_student]['exam'],2,',','')}}</p>
                            </td>
                            <td class="col global">
                                <p>{{($marks[$s->id_student]['global'] === '----') ? '' : number_format($marks[$s->id_student]['global'],2,',','')}}</p>
                            </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
    {{--Eliminado subjects view--}}
</div>
