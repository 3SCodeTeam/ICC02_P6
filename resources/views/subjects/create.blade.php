<div class="subjects create main container">
    @if(isset($admin))
        <h2>{{$course['name']}}</h2>
        <h3>{{$class_data->class_name}} (<a href="mailto:{{$user_data['email']}}">{{$user_data['email']}}</a>)</h3>
    @endif
    <div class="subjects create form container">
        <form action="{{asset('/subjects/subjectsPost/'.$id_class)}}" method="post" id="newSubject" >
            @csrf
            <div class="subjects create inputs radio">
                <input type="radio" id="work" name="type" value="works" required/>
                <label for="work">Trabajo</label>
                <input type="radio" id="exam" name="type" value="exams" required/>
                <label for="exam">Exámen</label>
            </div>
            <div class="subjects create inputs left">
                <label for="name">Nombre</label>
                {{--Impedir que se pueda utilizar '_' con pattern. Request reemplaza los espacios por '_'.--}}
                <input id="name" name="name" type="text" placeholder="Nombre del trabajo" pattern="[^_]+" required/>
                <label for="date">Fecha de entrega</label>
                <input id="date" name="date" type="date" min="{{date('Y-m-d')}}" max="{{$class_data->date_end}}" required/>
                <label for="time">Hora de entrega</label>
                <input id="time" name="time" type="time" required/>
            </div>
            <div class="subjects create inputs right">
                <label for="description" >Descripción</label>
                <textarea name="description" id="description" placeholder="Descripción..."></textarea>
            </div>
            <div class="subjects create inputs down">
                <input type="submit" value="Crear" />
            </div>
        </form>
    </div>
</div>
