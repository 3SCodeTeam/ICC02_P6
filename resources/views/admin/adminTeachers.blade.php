<div class="admin teachers container">
    <h2>Nuevo Profesor</h2>
            <div class="form-container teachers">
                <form action="{{asset('admin/teachersPost')}}" method="post">
                    @csrf
                    <div class="techers form-inputs container">
                        <input class="teachers form input" type="text" id="name" name="name" placeholder="Nombre del profesor" required/>
                        <input class="teachers form input" type="text" id="nif" name="nif" placeholder="NIF" required/><br>
                        <input class="teachers form input" type="text" id="surname" name="surname" placeholder="Apellido" required/>
                        <input class="teachers form input" type="tel" id="telephone" name="telephone" placeholder="Teléfono" required/><br>
                        <input class="teachers form input" type="email" id="email" name="email" placeholder="Email" required/>
                        <input class="teachers form input" type="password" id="password" name="password" placeholder="Contraseña" required/><br>
                    </div>
                    <div>
                        <input class="teachers form input button" type="submit" value="Añadir"/>
                    </div>
                </form>
            </div>
    @if(count($teachers_data)>0)
    <div class="teachers list container">
        <table><tbody>
            <tr class="row header">
                <th class="col name surname">Apellidos, nombre</th>
                <th class="col email">Email</th>
                <th class="col nif">NIF</th>
                <th class="col telephone">Teléfono</th>
            </tr>
            @foreach($teachers_data as $t)
                <tr class="row teacher">
                    <td class="col name surname"><span><a href="{{asset('admin/teachers/'.$t->id_teacher)}}">{{$t->surname.', '.$t->name}}</a></span></td>
                    {{--<td class="col surname"><span>{{$t->surname}}</span></td>--}}
                    <td class="col email"><span><a href="mailto:{{$t->email}}">{{$t->email}}</a></span></td>
                    <td class="col nif"><span>{{$t->nif}}</span></td>
                    <td class="col telephone"><span>{{$t->telephone}}</span></td>
                </tr>
            @endforeach
        </tbody></table>
    </div>
    @endif
</div>
