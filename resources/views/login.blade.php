<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link  rel="stylesheet" type="text/css" href="{{ asset('css/style.css')}}"/>
    <link  rel="stylesheet" type="text/css" href="{{ asset('css/login.css')}}"/>

    <title>3SCode Academy Manager</title>
</head>
<body>
    @include('header')
    <div class= "main-container">
        <div>
            <h1>Inicia sesión</h1>
            <span>o <a href="{{asset('/signin')}}">Registrate</a></span>
        </div>
        <div class="form-container">
            <div class="selector-container">
                <label for="rol">Perfil:</label>
                <select name="rol_option" id="rol" form="login" required>
                    <option value="student">Estudiante</option>
                    <option value="teacher">Profesor</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            <form action="{{asset('/login/post')}}" method="post" id="login">
                <div><input type="text" name="username" placeholder="Nombre de usuario" required></div>
                <div><input type="email" name="email" placeholder="Email" required></div>
                <div><input type="password" name="pass" placeholder="Contraseña" required></div>
                @csrf
                <div><input type="submit" value="Enviar"></div>
            </form>
        </div>
        <div class="alert-msg">
        @if (isset($msg))
            <div class="msg">{{$msg}}</div>
        @endif
        </div>
    </div>
    @include('footer')
    </body>
</html>
