<div class="admin-course-container">
    <h2>Nuevo curso</h2>
            <div class="form container courses">
                <form action="{{asset('admin/coursesPost')}}" method="post" id="courses">
                    @csrf
                    <div class="courses form inputs container">
                        {{--CONTENEDOR IZQUIERDO--}}
                        <div class="courses inputs container left">
                            <label for="name">Nombre</label>
                            <input class="courses form input" type="text" name="name" placeholder="Nombre del curso" required/><br>
                            <label for="date_start">Inicio</label>
                            <input class="courses form input" type="date" name="date_start" placeholder="Fecha de inicio del curso" required/>
                        </div>
                        {{--CONTENEDOR DERECHO--}}
                        <div class="courses inputs container right">
                            <label for="active">Estado</label>
                            <select id="active" name="active" form="courses">
                                <option value="1" selected>Activo</option>
                                <option value="0">Inactivo</option>
                            </select><br>
                            <label for="date_end">Fin</label>
                            <input class="courses form input" type="date" name="date_end" placeholder="Fecha fin de curso" required/>
                        </div>
                        {{--CONTENEDOR INFERIOR--}}
                        <div class="courses inputs container description">
                            <label for="description">Descripción</label>
                            <input class="courses form input description" type="text" name="description" required/><br><br><br>
                        </div>
                        <div class="courses inputs container button">
                            <input class="courses form input button" type="submit" value="Añadir"/>
                        </div>
                    </div>

                </form>
            </div>

            <div class="courses table container">
                <table><tbody>
                    <tr class="row header">
                        <th class="col name"><span>Nombre</span></th>
                        <th class="col start"><span>Inicio del curso</span></th>
                        <th class="col end"><span>Fin de curso</span></th>
                        <th class="col description"><span>Descripción</span></th>
                    </tr>
                    @foreach($courses_data as $c)
                        <tr class="row courses {{($c->active ==0) ? 'inactive':'active'}}">
                            <td class="col name"><span><a href="{{asset('/details/students/'.$c->id_course)}}">{{$c->name}}</a></span></td>
                            <td class="col start"><span>{{$c->date_start}}</span></td>
                            <td class="col end"><span>{{$c->date_end}}</span></td>
                            <td class="col description"><span>{{$c->description}}</span></td>
                        </tr>
                    @endforeach
                </tbody></table>
            </div>
</div>
