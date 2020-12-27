<div class="record main container">
    <form action="{{asset('/details/recordPost/'.$student['id_student'])}}" method="post" name="marks" id="marks">
        @csrf
        <div class="student data">
            <div>
                <div>Alumno: {{$student['surname'].', '.$student['name']}} <span><a href="mailto:{{$student['email']}}">({{$student['email']}})</a></span></div>
            </div>
            <div class="record submit container">
                <input type="submit" name="submit" value="Actualizar"/>
            </div>
        </div>
        <div class="record subject form">
            <div class="subjects head">
                <div class="col name">Exámenes y trabajos</div>
                <div class="col mark">Notas</div>
            </div>
            @foreach($exams as $e)
                <div class="subject data">
                    <div class="col name">{{$e->subject_name}} (examen)</div>
                    <div class="col mark"><input type="number" min="0" max="10" step="0.5" name="{{'exam;'.$e->id_exam}}" id="{{'exam;'.$e->id_exam}}" placeholder="{{($e->mark < 0) ? '' : $e->mark}}"/></div>
                </div>
            @endforeach
            @foreach($works as $w)
                <div class="subject data">
                    <div class="col name">{{$w->subject_name}} (trabajo)</div>
                    <div class="col mark"><input type="number" min="0" max="10" step="0.5" name={{'work;'.$w->id_work}} id="{{'work;'.$w->id_work}}" placeholder="{{($w->mark < 0) ? '' : $w->mark}}"/></div>
                </div>
            @endforeach
        </div>
    </form>
</div>
