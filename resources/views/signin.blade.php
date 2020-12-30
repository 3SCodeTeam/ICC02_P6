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
                <input class="signin-form-input" type="text" name="username" pattern="[a-zA-Z0-9]{4,}" placeholder="Nombre de usuario" required/>
                <input class="signin-form-input" type="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" placeholder="Email" required/>

                {{--pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" PASSWORD PATTERN--}}
                <input class="signin-form-input" type="password" name="password" pattern=".{6,}" placeholder="Contraseña" required/>
                <input class="signin-form-input" type="password" name="password_check" pattern=".{6,}" placeholder="Confirmar la contraseña" required/>

            </div>
            <div class="signin-form-input">
                <input class="signin-form-input" type="text" name="name" pattern="[[a-zA-ZáéíóúÁÉÍÓÚÜ]{3,}" placeholder="Nombre" required/>
                <input class="signin-form-input" type="text" name="surname" pattern="[a-zA-ZáéíóúÁÉÍÓÚÜ]{3,}" placeholder="Apellidos" required/>
                <input class="signin-form-input" type="tel" name="telephone" pattern="[0-9-]{9,}" placeholder="Teléfono" required/>
                <input class="signin-form-input" type="text" name="nif" pattern="([0-9]{9})+([TRWAGMYFPDXBNJZSQVHLCKE]{1})" placeholder="NIF"/>
            </div>
            <div class="signin notification selector container">
                <h4>Selecciona que tipo de notificaciones quieres recibir.</h4>
                <div class="notification checkbox">
                    <input type="checkbox" id="work" name="work" value="1"/>
                    <label for="work">Trabajo</label>
                    <input type="checkbox" id="exam" name="exam" value="1"/>
                    <label for="exam">Examen</label>
                    <input type="checkbox" id="continuous_assessment" name="continuous_assessment" value="1"/>
                    <label for="continuous_assessment">Evaluación conitnua</label>
                    <input type="checkbox" id="final" name="final" value="1"/>
                    <label for="final">Nota final</label>
                </div>
            </div>
            <div>
                <input class="signin-form-input" type="submit" value="Enviar"/>
            </div>
        </form>
    </div>
<div class="alert-msg">
    @if (isset($msg))
        <div class="msg">{{$msg}}</div>
    @endif
</div>
    @include('footer')
</body>
</html>
