<div class="subjectUpdate main container">
    <h3>Alumno: <span>{{$student['surname'].', '.$student['name']}}</span> <span>(<a href="mailto:{{$student['email']}}">{{$student['email']}}</a>)</span></h3>
    <div class="subjectUpdate form container">
        <form action="{{asset('detail/recordPost/'.$subject['type'].'/'.$subject['id_subject'])}}" method="POST" name="subject" id="subject">
            @csrf
            <div class="record inputs top">
                <input name="name" type="text" placeholder="{{$subject['name']}}" value="{{$subject['name']}}" required/>
                <input name="date" type="date" min="{{date('Y-m-d')}}" max="{{$course['date_end']}}" placeholder="{{$subject['date']}}" value="{{$subject['date']}}" required/>
                <input name="time" type="time" placeholder="{{$subject['time']}}" value="{{$subject['time']}}" required/>
            </div>
            <div class="record inputs bottom">
                <textarea name="description" type="text" placeholder="{{$subject['description']}}" value="{{$subject['description']}}"></textarea>
            </div>
            <div class="record inputs submit">
                <input type="submit" value="Actualizar"/>
            </div>
        </form>
    </div>
</div>
