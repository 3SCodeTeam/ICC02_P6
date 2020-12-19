<div class="teacher studentDetails container">
    <form action="{{asset('/teacher/students/')}}" method="post" name="marks" id="marks">
        <div class="student data">
            <div>
                <div>Nombre y apellidos</div>
                <div>email</div>
            </div>
            <div class="techer studentDetails submit container">
                <input type="submit" name="submit" value="Actualizar"/>
            </div>
        </div>
        <div class="teacher studentDetails subject form">
            <div class="subjects head">
            <div class="col name">Exámenes y trabajos</div>
            <div class="col mark">Notas</div>
            </div>
            @foreach($exams as $e)
                <div class="subject data">
                <div class="col name">{{$e->name}}</div>
                <div class="col mark"><input type="number" min="0" max="10" step="0.5" name="{{$e->name}}" id="{{$e->name}}" placeholder="{{($e->mark < 0) ? '' : $e->mark}}"/></div>
                </div>
            @endforeach
            @foreach($works as $w)
                <div class="subject data">
                <div class="col name">{{$w->name}}</div>
                <div class="col mark"><input type="number" min="0" max="10" step="0.5" name={{$w->name}} id="{{$w->name}}" placeholder="{{($w->mark < 0) ? '' : $w->mark}}"/></div>
                </div>
            @endforeach
        </div>
    </form>
</div>
