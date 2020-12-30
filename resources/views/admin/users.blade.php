<div class="users main container">
    <h3>Menú de gestión de estudiantes</h3>
    <div class="table container">
        <table>
            <thead>
            <tr>
                <th class="col del"><div class="cell del"><i class="fas fa-dumpster"></i></div></th>
                <th class="col pass"><div class="cell pass">Pass reset</div></th>
                <th class="col date"><div class="cell date">Ingreso</div></th>
                <th class="col name"><div class="cell name">Nombre</div></th>
                <th class="col surname"><div class="cell surname">Apellido</div></th>
                <th class="col email"><div class="cell email">Email</div></th>
                <th class="col tel"><div class="cell tel">Teléfono</div></th>
                <th class="col nif"><div class="cell nif">NIF</div></th>
            </tr>
            </thead>
            <tbody>
            @foreach($students as $s)
                <tr>
                    <td class="col del"><a href="{{asset('/admin/deleteStudent/'.$s->id)}}" ><div class="cell del"><i class="far fa-trash-alt"></i></div></a></td>
                    <td class="col pass"><a href="{{asset('/admin/resetPass/'.$s->id)}}" ><div class="cell pass"><button type="button">Reset</button></div></a></td>
                    <td class="col date"><div class="cell date">{{$s->date_registered}}</div></td>
                    <td class="col name"><div class="cell name">{{$s->name}}</div></td>
                    <td class="col surname"><div class="cell surname">{{$s->surname}}</div></td>
                    <td class="col email"><a href="mailto:{{$s->email}}" class="cell email">{{$s->email}}</a></td>
                    <td class="col tel"><div class="cell tel">{{$s->telephone}}</div></td>
                    <td class="col nif"><div class="cell nif">{{$s->nif}}</div></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
