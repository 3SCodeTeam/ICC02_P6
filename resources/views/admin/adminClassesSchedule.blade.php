<div class="classesSchedule main container">
    <div class="form container classSchedule">
        <form action="{{asset('admin/classesPostSchedule')}}" method="post" id="classesSchedule" name="classesSchedule">
            @csrf
            <div class="form text container">
                <div class="form container teacher course">
                    <h3>{{$formValues['course_name']}}</h3>
                    <h3>{{$formValues['teacher_email']}}</h3>
                </div>
                <div class="form container inputs">
                    <label for="color">Color:<br>
                    <input type="color" id="color" name="color" value="#FF0000" required/>
                    </label>
                    <label for="workWeight">Peso de la evaluación continua<br>
                    <input type="number" name="workWeight" id="work" min="10" max="90" step="5" value="60" required/>
                    </label>
                    <label for="name">Nombre de la asignatura<br>
                    <input type="text" name="name" id="name" required>
                    </label>

                    {{--ESTOS DOS PUNTOS LOS PODEMOS ELIMINAR CON UN $request->flash() en el controller--}}
                    <input type="hidden" id="course" name="course" value="{{$formValues['course']}}" readonly>
                    <input type="hidden" id="teacher" name="teacher" value="{{$formValues['teacher']}}" readonly>
                </div>
                <div class="form input submit">
                    <input type="submit" value="Crear">
                </div>
            </div>
            <div class="form table container">
                <table>
                    <thead>
                    <tr class="row header">
                        <th class="col hour"><p>HORA</p></th>
                        @foreach($freeHoursOfWeek['08:00'] as $k=>$v){{--POCO ELEGANTE--}}
                            <th class="col {{$k}}"><p>{{$k}}</p></th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($freeHoursOfWeek as $h=>$dow)
                            <tr class="row hour">
                                <td class="col hour">{{$h}}</td>
                                @foreach($dow as $d=>$v)
                                    <td class="col {{$d}}" style="background-color: {{($v) ? 'white':'#888888'}}"><input type="checkbox" name="{{$d.';'.$h}}" {{($v) ? '':'hidden'}}/> </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
