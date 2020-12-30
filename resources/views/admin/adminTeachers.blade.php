<div class="admin teachers container">
    <h2>Nuevo Profesor</h2>
            <div class="form-container teachers">
                <form action="{{asset('admin/teachersPost')}}" method="post">
                    @csrf
                    <div class="techers form-inputs container">
                        <input class="teachers form input" type="text" id="name" name="name" placeholder="Nombre del profesor" pattern="[a-zA-ZáéíóúÁÉÍÓÚÜ]{3,}" required/>
                        <input class="teachers form input" type="text" id="nif" name="nif" placeholder="NIF" pattern="([0-9]{9})+([TRWAGMYFPDXBNJZSQVHLCKE]{1})" required/><br>
                        <input class="teachers form input" type="text" id="surname" name="surname" placeholder="Apellido" pattern="[a-zA-ZáéíóúÁÉÍÓÚÜ]{3,}" required/>
                        <input class="teachers form input" type="tel" id="telephone" name="telephone" placeholder="Teléfono" pattern="[0-9-]{9,}" required/><br>
                        <input class="teachers form input" type="email" id="email" name="email" placeholder="Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"  required/>
                        <input class="teachers form input" type="password" id="password" name="password" placeholder="Contraseña" pattern=".{6,}" required/><br>
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
                <th class="col del">Alumnos</th>
                <th class="col name surname">Apellidos, nombre</th>
                <th class="col email">Email</th>
                <th class="col nif">NIF</th>
                <th class="col telephone">Teléfono</th>
            </tr>
            @foreach($teachers_data as $t)
                <tr class="row teacher">
                    @if($t->classes < 1)
                        <td class="col del">
                            <div class="cell del">
                                <a href="{{asset('/admin/deleteTeacher/'.$t->id_teacher)}}">
                                    <button type="button"><i class="fas fa-trash"></i></button>
                                </a>
                            </div>
                        </td>
                    @else
                        <td class="col del">
                            <div class="cell del disabled">
                                {{$t->classes}}
                            </div>
                        </td>
                    @endif
                    <td class="col name surname"><div class="cell name">{{$t->surname.', '.$t->name}}</div></td>
                    <td class="col email"><div class="cell email"><a href="mailto:{{$t->email}}">{{$t->email}}</a></div></td>
                    <td class="col nif"><div class="cell nif">{{$t->nif}}</div></td>
                    <td class="col telephone"><div class="cell tel">{{$t->telephone}}</div></td>
                </tr>
            @endforeach
        </tbody></table>
    </div>
    @endif
</div>
