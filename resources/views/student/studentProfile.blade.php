<div class="student profile main container">
    <H2>Datos del usuario</H2>
            <div class="profile-data-container">
                <div class="profile-data profile-username"><span>Nombre de usuario: </span>{{$user_data['username']}}</div>
                <div class="profile-data profile-name"><span>Nombre: </span>{{$user_data['name']}}</div>
                <div class="profile-data profile-surname"><span>Apellido: </span>{{$user_data['surname']}}</div>
                <div class="profile-data profile-email"><span>Email: </span>{{$user_data['email']}}</div>
                <div class="profile-data profile-nif"><span>NIF: </span>{{$user_data['nif']}}</div>
                <div class="profile-data profile-phone"><span>Teléfono: </span>{{$user_data['telephone']}}</div>
            </div>

            <div class="form-container">
                <div class="selector-container">
                    <label for="user_data">Seleccione el campo que desea actualizar:</label><br>
                    <select name="user_data_option" id="user_data_selector" form="profile_update" required>
                        <option value="notifications">Notificaciones</option>
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
                    <input class="profile-form input-field" type="text" id='value' name='value'>
                    <div>
                        <input class="profile-form input-button" type="submit" value="Modificar"/>
                    </div>
                    <div class="signin notification selector container">
                        <h4>Notificaciones:</h4>
                        <div class="notification checkbox">
                            <input type="checkbox" id="work" name="work" value="1" {{($notifications->work == 1 ? 'checked': '')}}/>
                            <label for="work">Trabajo</label>
                            <input type="checkbox" id="exam" name="exam" value="1" {{($notifications->exam == 1 ? 'checked': '')}}/>
                            <label for="exam">Examen</label>
                            <input type="checkbox" id="continuous assessment" name="continuous assessment" value="1" {{($notifications->continuous_assessment == 1 ? 'checked': '')}}/>
                            <label for="continuous assessment">Evaluación conitnua</label>
                            <input type="checkbox" id="final" name="final" value="1" {{($notifications->final_note == 1 ? 'checked': '')}}/>
                            <label for="final">Nota final</label>
                        </div>
                    </div>
                </form>
            </div>
</div>
