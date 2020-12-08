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
                <input class="signin-form-input" type="text" name="username" placeholder="Nombre de usuario" required/>
                <input class="signin-form-input" type="email" name="email" placeholder="Email" required/>
                <input class="signin-form-input" type="password" name="password" placeholder="Contraseña" required/>
                <input class="signin-form-input" type="password" name="password_check" placeholder="Confirmar la contraseña" required/>
            </div>
            <div class="signin-form-input">
                <input class="signin-form-input" type="text" name="name" placeholder="Nombre" required/>
                <input class="signin-form-input" type="text" name="surname" placeholder="Apellidos" required/>
                <input class="signin-form-input" type="tel" name="telephone" placeholder="Teléfono" required/>
                <input class="signin-form-input" type="text" name="nif" placeholder="NIF"/>

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
