<div class="updateSubjects main container">
    @if(isset($admin))
        <h2>{{$course['name']}}</h2>
        <h3>{{$class_data->class_name}} (<a href="mailto:{{$user_data['email']}}">{{$user_data['email']}}</a>)</h3>
    @else
        <h3>{{$class_data->class_name}} (<a href="{{asset('/teachers/students/'.$class_data->id_class)}}"><span>{{$course['name']}}</span></a>)</h3>
    @endif
    <div class="updateSubjects form container">
            <table class="updateSubjects table">
                <thead>
                    <tr>
                        <th class="type">Tipo</th>
                        <th class="name">Nombre</th>
                        <th class="date">Fecha de entrega</th>
                        <th class="time">Hora de entrega</th>
                        <th class="description">Descripción</th>
                        <th class="submit">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($selectedSubjects as $s)
                        <form action="{{asset('/subjects/updatePost/'.$class_data->id_class)}}" method="post" id="updateSubject" name="{{$s['type'].';'.$s['name']}}">
                        <tr>
                            <td class="col type"><div class="updateSubject form div">{{$s['type']}}</div></td>
                            <td class="col name"><div class="updateSubject form input"><input name="name" type="text" placeholder="{{$s['name']}}"/></div></td>
                            <td class="col date"><div class="updateSubject form input"><input name="date" type="date" min="{{date('Y-m-d')}}" max="{{$class_data->date_end}}" placeholder="{{$s['date']}}" value="{{$s['date']}}"/></div></td>
                            <td class="col time"><div class="updateSubject form input"><input name="time" type="time" placeholder="{{$s['time']}}" value="{{$s['time']}}"/></div></td>
                            <td class="col description"><div class="updateSubject form input"><textarea name="description" type="text" placeholder="{{$s['description']}}"></textarea></div></td>
                            <td class="col submit"><div class="updateSubject form input"><input type="submit" value="Actualizar"/></div></td>
                        </tr>
                            @csrf
                            <input hidden value="{{$s['name']}}" name="subject_name">
                            <input hidden value="{{$s['type']}}" name="type"/>
                        </form>
                    @endforeach
                </tbody>
            </table>
    </div>
</div>
