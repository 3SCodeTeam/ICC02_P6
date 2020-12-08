<div class="admin-profile-container">
    <H2>Datos del usuario</H2>
            <div class="profile-data-container">
                <div class="profile-data profile-username"><span>Nombre de usuario: </span>{{$user_data->username}}</div>
                <div class="profile-data profile-name"><span>Nombre: </span>{{$user_data->name}}</div>
                <div class="profile-data profile-surname"><span>Apellido: </span>{{$user_data->surname}}</div>
                <div class="profile-data profile-email"><span>Email: </span>{{$user_data->email}}</div>
                <div class="profile-data profile-nif"><span>NIF: </span>{{$user_data->nif}}</div>
                <div class="profile-data profile-phone"><span>Teléfono: </span>{{$user_data->telephone}}</div>
            </div>

            <div class="form-container">
                <div class="selector-container">
                    <label for="user_data">Seleccione el campo que desea actualizar:</label><br>
                    <select name="user_data_option" id="user_data_selector" form="profile_update" required>
                        <option value="username">Nombre de usuario</option>
                        <option value="name">Nombre</option>
                        <option value="surname">Apellido</option>
                        <option value="email">Email</option>
                        <option value="nif">NIF</option>
                        <option value="telephone">Teléfono</option>
                        <option value="password">Contraseña</option>
                    </select>
                </div>
                <form action="{{asset('/student/profilePost')}}" method="post" id="profile_update">
                    @csrf
                    <input class="profile-form input-field" type="text" id='value' name='value' required>
                    <div>
                        <input class="profile-form input-button" type="submit" value="Modificar"/>
                    </div>
                </form>
            </div>
</div>
