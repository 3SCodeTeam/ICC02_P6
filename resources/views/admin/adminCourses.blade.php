<div class="courses main container">
    <h3>Nuevo curso</h3>
            <div class="form container courses">
                <form action="{{asset('admin/coursesPost')}}" method="post" id="courses">
                    @csrf
                    <div class="courses form container">

                        <div class="inputs container">
                            <div class="input name">
                                <label for="name">Nombre</label>
                                <input class="courses form input" type="text" id="name" name="name" placeholder="Nombre del curso" pattern=".{10,255}" required/>
                            </div>
                            <div class="input date_start">
                                <label for="date_start">Inicio</label>
                                <input class="courses form input" type="date" id="date_start" name="date_start" placeholder="Fecha de inicio del curso" required/>
                            </div>
                            <div class="input date_end">
                                <label for="date_end">Fin</label>
                                <input class="courses form input" type="date" id="date_end" name="date_end" placeholder="Fecha fin de curso" required/>
                            </div>
                            <div class="input state">
                                <label for="active">Estado</label>
                                <select id="active" name="active" form="courses">
                                    <option value="1" selected>Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                            <div class="input description">
                                <label for="description">Descripción</label>
                                <textarea id="description" class="courses form input description" name="description" pattern=".{10,500}" required></textarea>
                            </div>
                            <div class="input button">
                                <input class="courses form input button" type="submit" value="Añadir"/>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="courses table container">
                <table><thead>
                    <tr class="row header">
                        <th class="col del"><div class="cell del"></div></th>
                        <th class="col action"><div class="cell action"></div></th>
                        <th class="col name"><div class="cell name">Nombre</div></th>
                        <th class="col start"><div class="cell date_start">Inicio del curso</div></th>
                        <th class="col end"><div class="cell date_end">Fin de curso</div></th>
                        <th class="col description"><div class="cell description">Descripción</div></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($courses_data as $c)
                        <tr class="row courses {{($c->active == 0) ? 'inactive':'active'}}">
                            <td class="col del">
                                <a href="{{asset('/admin/deleteCourse/'.$c->id_course)}}">
                                    <button type="button"><i class="fas fa-trash"></i></button>
                                </a>
                            </td>
                            <td class="col action">
                                <a href="{{asset('/admin/courseActive/'.$c->id_course)}}">
                                    @if($c->active == 0)
                                        <i class="fas fa-toggle-off" style="color:red;"></i>
                                    @else
                                        <i class="fas fa-toggle-off" style="color:green;"></i>
                                    @endif
                                    {{--<button type="button">{{(($c->active == 0) ? 'ON':'OFF')}}</button>--}}
                                </a>
                            </td>
                            <td class="col name"><a href="{{asset('/details/students/'.$c->id_course)}}"><div class="cell name">{{$c->name}}</div></a></td>
                            <td class="col start"><div class="cell date_start">{{$c->date_start}}</div></td>
                            <td class="col end"><div class="cell date_end">{{$c->date_end}}</div></td>
                            <td class="col description"><div class="cell description">{{$c->description}}</div></td>
                        </tr>
                    @endforeach
                </tbody></table>
            </div>
</div>
