<div class="teacher profile container">
    <div class="teacher profile form container">
        <h4>Datos del perfil</h4>
        <form class="teacher profile form" action="/teachers/profilePost" method="post" id="teacher-profile-form" name="teacher-profile-form">
            @csrf
            <div class="teacher profile inputs left container">
                <label for="name">Nombre</label>
                <input type="text" name="name" id="name" placeholder="{{$user_data['name']}}"/>
                <label for="name">Apellidos</label>
                <input type="text" name="surname" id="surname" placeholder="{{$user_data['surname']}}"/>
                <label for="name">Teléfono</label>
                <input type="tel" name="phone" id="phone" placeholder="{{$user_data['telephone']}}"/>
                <label for="name">NIF</label>
                <input type="text" name="nif" id="nif" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" placeholder="{{$user_data['nif']}}"/>
            </div>
            <div class="teacher profile inputs right container">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="{{$user_data['email']}}"/>
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" pattern=".{6,}"/> {{--pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"--}}
                <label for="password_confirmation">Verificar contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" pattern=".{6,}" />
            </div>
            <div class="teacher profile inputs down">
                <input type="submit" name="submit" value="Actualizar" id="submit"/>
                <input type="reset" name="Restablecer" id="reset"/>
            </div>
        </form>
    </div>
</div>
