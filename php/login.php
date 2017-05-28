<?php
    // Almacenamos los datos que ha introducido el usuario en el formulario como variables:
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Abrimos la base de datos y guardamos la conexión (no la base de datos entera) en una variable:
    // ('servidor, 'usuario', 'contraseña', 'nombre de la base de datos')
    $db = @mysqli_connect('localhost', 'root', '', 'melomaniac');
    // Establecemos el cotejamiento como el de la base de datos para que muestre correctamente
    // los caracteres acentuados:
    @mysqli_set_charset($db, 'utf8');
    // Utilizamos el carácter @ para no mostrar las alertas generadas por las funciones en caso de error.

    // Consulta para buscar a un usuario en la tabla users de la base de datos.
    $sql_check_user = "SELECT * FROM users WHERE username = '$username'";
    $query_check_user = mysqli_query($db, $sql_check_user);

    // Comprobamos si efectivamente el usuario existe en la base de datos:
    if ($query_check_user != null) {
        // Comprobamos que la contraseña devuelta por la consulta corresponde con la introducida
        // por el usuario en el login:
        $object = mysqli_fetch_object($query_check_user);

        if (password_verify($password, $object->password)) {
            // Iniciamos la sesión:
            session_start();
            $_SESSION['login'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['name'] = $object->name;

            // Si el usuario tiene rol de administración, se le autoriza para gestionar la página:
            if($object->admin == true) {
                $_SESSION['admin'] = true;
            } else {
                $_SESSION['admin'] = false;
            }

            // La función header nos redirige a la página de inicio:
            header('Location: ../index.php');
        }
        else {
            // En caso de error, se devuelve un error de tipo error_login.
            header('Location: ../index.php?error=error_login');
        }
    }
    else {
        header('Location: ../index.php?error=error_login');
    }

    // Cerramos la conexión por seguridad:
    @mysqli_close($db);
?>
