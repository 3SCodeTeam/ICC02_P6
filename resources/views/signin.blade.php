<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>3SCode Academy Manager</title>
    <link  rel="stylesheet" type="text/css" href="{{ asset('css/style.css')}}"/>
    <link  rel="stylesheet" type="text/css" href="{{ asset('css/signin.css')}}"/>
</head>
<body>
@include('header')
    <div>
        <h1>Registrate</h1>
                <span>o bien <a href="{{asset('/login')}}">Inicia sesión.</a></span>
    </div>
    <div class="main-container">
        <form action="{{asset('/signin/post')}}" method="post">
            @csrf
            <div class="signin-form-input">
                <input class="signin-form-input" type="text" name="username" patter="[a-zA-Z0-9]" placeholder="Nombre de usuario" required/>
                <input class="signin-form-input" type="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" placeholder="Email" required/>
                <input class="signin-form-input" type="password" name="password" pattern=".{6,}" placeholder="Contraseña" required/>
                <input class="signin-form-input" type="password" name="password_check" pattern=".{8,}" {{--pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"--}} placeholder="Confirmar la contraseña" required/>
            </div>
            <div class="signin-form-input">
                <input class="signin-form-input" type="text" name="name" patter="[a-zA-Z]{3,}" placeholder="Nombre" required/>
                <input class="signin-form-input" type="text" name="surname" patter="[a-zA-Z]{3,}" placeholder="Apellidos" required/>
                <input class="signin-form-input" type="tel" name="telephone" patter="[0-9-]{9,}" placeholder="Teléfono" required/>
                <input class="signin-form-input" type="text" name="nif" patter="[0-9]{9}+[A-Z]{1}" placeholder="NIF"/>
            </div>
            <div class="signin notification selector container">
                <h4>Selecciona que tipo de notificaciones quieres recibir.</h4>
                <div class="notification checkbox">
                    <input type="checkbox" id="work" name="work" value="1"/>
                    <label for="work">Trabajo</label>
                    <input type="checkbox" id="exam" name="exam" value="1"/>
                    <label for="exam">Examen</label>
                    <input type="checkbox" id="continuous assessment" name="continuous assessment" value="1"/>
                    <label for="continuous assessment">Evaluación conitnua</label>
                    <input type="checkbox" id="final" name="final" value="1"/>
                    <label for="final">Nota final</label>
                </div>
            </div>
            <div>
                <input class="signin-form-input" type="submit" value="Enviar"/>
            </div>
        </form>
    </div>
    @if (isset($msg))
        <div class="msg">{{$msg}}</div>
    @endif
    @include('footer')
</body>
</html>
