<div class="main container classes details">
    <h2>{{$course['name']}}</h2>
    <div class="container classes">
        <h3>Asignaturas</h3>
        @foreach($classes as $c)

            <div class="class container">
                <span><a href="{{asset('details/subjects/'.$c->id_class)}}">{{$c->class_name}}</a></span>
                <span><a href="{{asset('details/teachers/'.$c->id_teacher)}}">{{$c->surname.', '.$c->teacher_name}}</a></span>
                <span><a href="mailto:{{$c->email}}">{{$c->email}}</a></span>
            </div>
        @endforeach
    </div>
</div>
