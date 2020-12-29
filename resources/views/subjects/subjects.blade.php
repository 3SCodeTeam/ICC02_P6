<div class="subjects main container">
    @if(isset($admin))
        <h2>{{$course['name']}}</h2>
        <h3>{{$class_data->class_name}} (<a href="mailto:{{$user_data['email']}}">{{$user_data['email']}}</a>)</h3>
    @else
        <h3>{{$class_data->class_name}} (<a href="{{asset('/teachers/students/'.$class_data->id_class)}}"><span>{{$course['name']}}</span></a>)</h3>
    @endif
    @if(count($subjects['works'])>0 || count($subjects['exams'])>0)
        <form action="{{asset('/subjects/update/'.$id_class)}}" name="subjects" id="subjects" method="post">
            <div class="subjects classDetails subjects submit">
                {{--<label for="action">Acción:</label>--}}
                <select name="action" id="action">
                    <option value="delete">Borrar</option>
                    <option value="update" selected>Actualizar</option>
                </select>
                <div class="teacher classDetails submit">
                    <input type="submit" value="Ejecutar">
                </div>
                <div class="subjects classDetails subjects newSubject">
                    <a href="{{asset('/subjects/create/'.$id_class)}}"><button type="button">Crear nuevo</button></a>
                </div>
            </div>
            @csrf
            <div class="subjects classDetails subjects exams">
                @if(count($subjects['exams'])>0)
                    <h3>Exámenes</h3>
                    <table class="subjects classDetails subjects exams table">
                        <thead>
                        <tr class="row header">
                            <th class="col selector"><span class="checkbox"> </span></th>
                            <th class = "col name"><p>Nombre</p></th>
                            <th class = "col date"><p>Fecha de entrega</p></th>
                            <th class = "col description"><p>Descripción</p></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($subjects['exams'] as $e)
                            <tr class="row data">
                                <td class="col selector"><div class="cell checkbox"><input type="checkbox" value='exam' name="{{'exam;'.$e->name}}" id="{{$e->name}}"></div></td>
                                <td class="col name"><label for="{{$e->name}}"><div class="cell">{{$e->name}}</div></label></td>
                                <td class="col date"><div class="cell">{{$e->deadline}}</div></td>
                                <td class="col description"><div class="cell">{{$e->description}}</div></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <div class="subjects classDetails subjects works">
                @if(count($subjects['works'])>0)
                    <h3>Trabajos</h3>
                    <table class="subjects classDetails subjects works table">
                        <thead>
                        <tr class="row header">
                            <th class="col selector"><span class="checkbox"> </span></th>
                            <th class = "col name"><p>Nombre</p></th>
                            <th class = "col date"><p>Fecha de entrega</p></th>
                            <th class = "col description"><p>Descripción</p></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($subjects['works'] as $w)
                            <tr class="row data">
                                <td class="col selector"><div class="cell checkbox"> <input type="checkbox" value='work' name="{{'work;'.$w->name}}" id="{{$w->name}}"></div></td>
                                <td class="col name"><label for="{{$w->name}}"><div class="cell">{{$w->name}}</div></label></td>
                                <td class="col date"><div class="cell">{{$w->deadline}}</div></td>
                                <td class="col description"><div class="cell">{{$w->description}}</div></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </form>
    @else
        <div class="subjects classDetails subjects newSubject">
            <a href="{{asset('/subjects/create/'.$id_class)}}"><button type="button">Crear nuevo</button></a>
        </div>
    @endif
</div>
