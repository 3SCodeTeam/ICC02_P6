<div class="subjects create main container">
    <div class="subjects create form container">
        <form action="{{asset('/subjects/subjectsPost/'.$id_class)}}" method="post" id="newSubject" >
            @csrf
            <div class="subjects create inputs radio">
                <input type="radio" id="work" name="type" value="works" />
                <label for="work">Trabajo</label>
                <input type="radio" id="exam" name="type" value="exams" />
                <label for="exam">Exámen</label>
            </div>
            <div class="subjects create inputs left">
                <label for="name">Nombre</label>
                <input id="name" name="name" type="text" placeholder="Nombre del trabajo" required />
                <label for="date">Fecha de entrega</label>
                <input id="date" name="date" type="date" min="{{date('Y-m-d')}}" max="{{$class_data->date_end}}" />
                <label for="time">Hora de entrega</label>
                <input id="time" name="time" type="time"/>
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
