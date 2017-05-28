<?php
    // Guardamos en variables los datos que nos llegan del formulario POST:
    $groupname = htmlentities($_POST['name'], ENT_QUOTES, UTF-8);
    $music = $_POST['music'];
    $min_age = $_POST['min_age'];
    $max_age = $_POST['max_age'];

    // Abrimos la base de datos y guardamos la conexión (no la base de datos entera) en una variable:
    // ('servidor, 'usuario', 'contraseña', 'nombre de la base de datos')
    $db = @mysqli_connect('localhost', 'root', '', 'melomaniac');
    // Utilizamos el carácter @ para no mostrar las alertas generadas por las funciones en caso de error.

    session_start();

    // Consulta para añadir el grupo en la tabla groups.
    $sql_create_group = "INSERT INTO groups(name, music, min_age, max_age) VALUES ('$groupname', '$music', '$min_age', '$max_age')";
    $query_create_group = mysqli_query($db, $sql_create_group);

    // Consulta para buscar todos los usuarios de la tabla users que cumplan las condiciones necesarias para
    // pertenecer a ese grupo (es decir, estar en el rango de edad y tener el mismo gusto musical):
    $sql_usersgroup = "SELECT * FROM users WHERE users.music='$music' AND '$min_age' <= TIMESTAMPDIFF(YEAR, users.age, CURDATE()) AND '$max_age' >= TIMESTAMPDIFF(YEAR, users.age, CURDATE())";
    $query_usersgroup = mysqli_query($db, $sql_usersgroup);
    $user = mysqli_fetch_object($query_usersgroup);

    while ($user != NULL) {
        // Consulta para añadir a la tabla usergroups una relación de los grupos a los que pertenece cada usuario:
        $sql_add_users = "INSERT INTO usergroups(username, groupname) VALUES ('$user->username', '$groupname')";
        $query_add_users = mysqli_query($db, $sql_add_users);
        $user = mysqli_fetch_object($query_usersgroup);
    }

    // Si se ha enviado correctamente, se nos redirige a la página de administración.
    // En caso de error, devuelve un error de tipo error_group.
    if ($query_create_group != NULL && $query_usersgroup != NULL) {
        header('Location: ../admin.php');
    } else {
        header('Location: ../admin.php?error=error_group');
    }

    // Cerramos la conexión por seguridad:
    @mysqli_close($db);
?>
