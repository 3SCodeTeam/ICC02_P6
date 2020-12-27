<div class="main container student details">
    <h2>{{$course['name']}}</h2>
    <div class="container details">
        <h3>Estudiantes</h3>

            <table>
                <thead>
                <tr>
                    <th class="surname">Apellidos</th>
                    <th class="name">Nombre</th>
                    <th class="email">Email</th>
                    <th class="tel">Teléfono</th>
                </tr>
                </thead>
                <tbody>
                @foreach($students as $s)
                    <tr>
                        <td class="surname">
                            <a href="{{asset('/details/subjectsOfStudent/'.$course['id_course'].'/'.$s->id)}}">
                                <div clas="cell">{{$s->surname}}</div>
                            </a>
                        </td>
                        <td class="name">
                            <a href="{{asset('/details/subjectsOfStudent/'.$course['id_course'].'/'.$s->id)}}">
                                <div class="cell">{{$s->name}}</div>
                            </a>
                        </td>
                        <td class="email"><div class="cell"><a href="mailto:{{$s->email}}">{{$s->email}}</a></div></td>
                        <td class="tel"><div class="cell">{{$s->telephone}}</div></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
    </div>
</div>
