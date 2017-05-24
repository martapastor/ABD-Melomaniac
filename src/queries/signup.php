<?php
    // Almacenamos los datos que ha introducido el usurio en el formulario como variables:
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $age = $_POST['age'];
    $music = $_POST['music'];

    // Abrimos la base de datos y guardamos la conexión (no la base de datos entera) en una variable:
    // ('servidor, 'usuario', 'contraseña', 'nombre de la base de datos')
    $db = @mysqli_connect('localhost', 'root', '', 'melomaniac');
    // Establecemos el cotejamiento como el de la base de datos para que guarde correctamente
    // los caracteres acentuados:
    @mysqli_set_charset($db, 'utf8');
    // Utilizamos el carácter @ para no mostrar las alertas generadas por las funciones en caso de error.

    // Instrucción que le vamos a mandar a la base de datos para dar de alta al usuario:
    $encryptedpassword = password_hash($password, PASSWORD_DEFAULT);
    $sql_add_user = "INSERT INTO users(name, email, username, password, music, age) VALUES ('$name', '$email', '$username', '$encryptedpassword', '$music', '$age')";

    // Realizamos la consulta en la base de datos (mandamos la instrucción anterior) y nos va a devolver
    // si se ha completado (entonces devuelve la consulta), y si no devuelve NULL:
    $query_add_user = mysqli_query($db, $sql_add_user);

    // Comprobamos si efectivamente se ha podido dar de alta al usuario antes de iniciarle la sesión:
    if ($query_add_user != null) {
        // Iniciamos la sesión:
        session_start();
        $_SESSION['username'] = $username;

        // La función header nos redirige a la página de inicio:
        header('Location: ../index.php');
    }
    else {
        header('Location: ../signup.php?error=error_signup');
    }

    // Cerramos la conexión por seguridad, y si necesitamos volver a abrirla para enviar un mensaje, se abre:
    @mysqli_close($db);
?>
