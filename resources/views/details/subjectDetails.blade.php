<div class="main container assessment details">
    <h2>{{$course}}</h2>
    <div class="container assessment details">
    <div class="container assessment exams">
        <h3>Exámenes {{$percentage->exams}}</h3>
        @foreach($exams as $e)
            <div class="container exam">{{$e->name}}</div>
        @endforeach
    </div>
    <div class="container assessment works">
        <h3>Trabajos {{$percentage->continuous_assessment}}</h3>
        @foreach($works as $w)
            <div class="container work">{{$w->name}}</div>
        @endforeach
    </div>
    </div>
</div>
