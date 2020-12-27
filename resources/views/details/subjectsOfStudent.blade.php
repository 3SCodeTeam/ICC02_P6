<div class="subjectsOfStudent main contianre">
    <h3>Alumno: <span>{{$student['surname'].', '.$student['name']}}</span> <span>(<a href="mailto:{{$student['email']}}">{{$student['email']}}</a>)</span></h3>
    @if(isset($subject))
        <div class="subjectsOfStudent form container">
            <form action="{{asset('/details/recordPost/')}}" method="post" name="marks" id="marks">
                @csrf
                <div class="subject data">
                    <div class="col name">{{$subject['name'].' ('.$subject['type'].')'}}</div>
                    <div class="col mark">
                        <input type="number" min="0" max="10" step="0.5" name="mark" id="mark" placeholder="{{($subject['mark'] < 0) ? '' : $subject['mark']}}" required/>
                        <input type="text" name="type" hidden value="{{$subject['type']}}">
                        <input type="text" name="id_subject" hidden value="{{$subject['id_subject']}}">
                    </div>
                </div>
                <div class="record submit container">
                    <input type="submit" name="submit" value="Actualizar"/>
                </div>
            </form>
        </div>
    @endif
    <div class="subjectsOfStudent table container">
        <table>
            <thead>
            <tr>
                <th class="type">Tipo</th>
                <th class="class">Asignatura</th>
                <th class="subject_name">Trabajo/Examen</th>
                <th class="deadline">Fecha de entrega</th>
                <th class="teacher">Profesor</th>
                <th class="mark">Nota</th>
            </tr>
            </thead>
            <tbody>
            @foreach($subjects as $s)
                <td class="type">
                    <a href="{{($s->type == "exam") ? asset('details/records/exam/'.$s->id_exam) : asset('details/records/work/'.$s->id_work)}}">
                        <div class="cell">{{$s->type}}</div>
                    </a>
                </td>
                <td class="class">
                    <a href="{{($s->type == "exam") ? asset('details/records/exam/'.$s->id_exam) : asset('details/records/work/'.$s->id_work)}}">
                        <div class="cell">{{$s->class_name}}</div>
                    </a>
                </td>
                <td class="subject_name">
                    <a href="{{($s->type == "exam") ? asset('details/records/exam/'.$s->id_exam) : asset('details/records/work/'.$s->id_work)}}">
                        <div class="cell">{{$s->subject_name}}</div>
                    </a>
                </td>
                <td class="deadline">
                    <a href="{{($s->type == "exam") ? asset('details/records/exam/'.$s->id_exam) : asset('details/records/work/'.$s->id_work)}}">
                    <div class="cell">{{$s->subject_deadline}}</div>
                    </a>
                </td>
                <td class="teacher">
                    <div class="cell">{{$s->teacher_surname.', '.$s->teacher_name}} (
                        <a href="mailto:{{$s->teacher_email}}">{{$s->teacher_email}}</a>)
                    </div>
                </td>
                <td class="mark">
                    <a href="{{($s->type == "exam") ? asset('details/records/exam/'.$s->id_exam) : asset('details/records/work/'.$s->id_work)}}">
                        <div class="cell">{{$s->mark}}</div>
                    </a>
                </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
