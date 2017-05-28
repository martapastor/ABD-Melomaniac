<?php
    // Abrimos la base de datos y guardamos la conexión (no la base de datos entera) en una variable:
    // ('servidor, 'usuario', 'contraseña', 'nombre de la base de datos')
    $db = @mysqli_connect('localhost', 'root', '', 'melomaniac');
    // Utilizamos el carácter @ para no mostrar las alertas generadas por las funciones en caso de error.

    session_start();

    $sql_get_users= "SELECT username FROM users ORDER BY name DESC";

    // Consulta para seleccionar todos los usuarios de la base de datos para enviar un mensaje personal.
    $query_get_users = mysqli_query($db, $sql_get_users);
    $object_user = mysqli_fetch_object($query_get_users);

    $users = array();

    while($object_user != NULL) {
        $users[] = $object_user->username;
        $object_user = mysqli_fetch_object($query_get_users);
    }

    // Devolvemos un array codificado como JSON con todos los usuarios registrados:
    echo json_encode($users);

    // Cerramos la conexión por seguridad:
    @mysqli_close($db);
?>
