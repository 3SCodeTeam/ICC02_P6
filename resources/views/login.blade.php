<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>3SCode Academy Manager</title>
    <link rel="stylesheet" href="resources/css/style.css">
    <link rel="stylesheet" href="resources/css/login.css">
</head>
<body>
    <div class= "main-container">
        <div>
            <h1>Inicia sesión</h1>
            <span>o <a href="http://localhost/?controller=signin&method=new">Registrate</a></span>
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
            <form action="/?controller=login&method=post" method="post" id="login">
                <div><input type="text" name="username" placeholder="Nombre de usuario" required></div>
                <div><input type="email" name="email" placeholder="Email" required></div>
                <div><input type="password" name="pass" placeholder="Contraseña" required></div>
                <div><input type="submit" value="Enviar"></div>
            </form>
        </div>
        @if (isset($errmsg))
            <div class="errmsg">{{$errmsg}}</div>
        @endif
    </div>
    <?php /*include("Recursos/html/footer.html"); */?>
</body>
</html>
