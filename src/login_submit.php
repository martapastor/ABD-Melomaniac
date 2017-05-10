<?php
    // Almacenamos los datos que ha introducido el usuario en el formulario como variables:
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Abrimos la base de datos y guardamos la conexión (no la base de datos entera) en una variable:
    // ('servidor, 'usuario', 'contraseña', 'nombre de la base de datos')
    $db = @mysqli_connect('localhost', 'root', '', 'melomaniac');
    // Utilizamos el carácter @ para no mostrar las alertas generadas por las funciones en caso de error.

    // En caso de que algún parámetro esté mal y no se pueda conectar a la base de datos, mostrar
    // un mensaje al usuario "Contacte con el administrador" blablabla

    // Instrucción que le vamos a mandar a la base de datos para dar de alta al usuario:
    // ENCRIPTAR CONTRASEÑA
    $sql_check_user = "SELECT password FROM users WHERE username = '$username'";

    // Realizamos la consulta en la base de datos (mandamos la instrucción anterior) y nos va a devolver
    // si se ha completado (entonces devuelve la consulta), y si no devuelve NULL:
    $query_check_user = mysqli_query($db, $sql_check_user);

    // Comprobamos si efectivamente el usuario existe en la base de datos:
    if ($query_check_user != null) {
        // Comprobamos que la contraseña devuelta por la consulta corresponde con la introducida
        // por el usuario en el login:
        $object = mysqli_fetch_object($query_check_user);
        if ($object->password == $password) {
            // Iniciamos la sesión:
            session_start();
            $_SESSION['username'] = $username;

            // La función header nos redirige a la página de inicio:
            header('Location: home.php');
        }
        else {
            header('Location: ../src/login_form.php?error=error_login');
        }
    }
    else {
        header('Location: ../src/login_form.php?error=error_login');
    }

    // Cerramos la conexión por seguridad, y si necesitamos volver a abrirla para enviar un mensaje, se abre:
    @mysqli_close($db);
?>
