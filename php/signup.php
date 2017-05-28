<?php
    // Almacenamos los datos que ha introducido el usurio en el formulario como variables:
    $name = nl2br(htmlentities($_POST['name'], ENT_QUOTES, UTF-8));
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

    // Encriptamos la contraseña con un hash para no guardarla como texto plano en la base de datos:
    $encryptedpassword = password_hash($password, PASSWORD_DEFAULT);

    // Consulta para añadir un usuario a la tabla users de la base de datos.
    $sql_add_user = "INSERT INTO users(name, email, username, password, music, age) VALUES ('$name', '$email', '$username', '$encryptedpassword', '$music', '$age')";
    $query_add_user = mysqli_query($db, $sql_add_user);

    // Consulta para añadir directamente las relaciones de el usuario que se acaba de dar de alta
    // con los grupos a los que pertenece según gusto musical y rango de edad.
    $sql_usergroup = "SELECT * FROM groups WHERE groups.music='$music' AND groups.min_age <= TIMESTAMPDIFF(YEAR, '$age', CURDATE()) AND groups.max_age >= TIMESTAMPDIFF(YEAR, '$age', CURDATE())";
    $query_usergroup = mysqli_query($db, $sql_usergroup);
    $group = mysqli_fetch_object($query_usergroup);

    while ($group != NULL) {
        // Anadimos a la tabla usergroups una relación de los grupos a los que pertenece cada usuario:
        $sql_add_users = "INSERT INTO usergroups(username, groupname) VALUES ('$username', '$group->name')";
        $query_add_users = mysqli_query($db, $sql_add_users);

        $group = mysqli_fetch_object($query_usergroup);
    }

    // Comprobamos si efectivamente se ha podido dar de alta al usuario antes de iniciarle la sesión:
    if ($query_add_user != null) {
        // Iniciamos la sesión:
        session_start();
        $_SESSION['login'] = true;
		$_SESSION['admin'] = false;
        $_SESSION['username'] = $username;

        // La función header nos redirige a la página de inicio:
        header('Location: ../index.php');
    }
    else {
        // En caso de error, se devuelve un error de tipo error_signup.
        header('Location: ../index.php?error=error_signup');
    }

    // Cerramos la conexión por seguridad:
    @mysqli_close($db);
?>
