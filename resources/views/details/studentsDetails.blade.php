<div class="main container student details">
    <h2>{{$course}}</h2>
    <div class="container details">
        <h3>Estudiantes</h3>
        @foreach($student as $s)
            <div class="student container">
                <span>{{$s->surname.', '.$s->name}}</span>
                <span><a href="mailto:{{$s->email}}">{{$s->email}}</a></span>
                <span>{{$s->telephone}}</span></div>
        @endforeach
    </div>
</div>
