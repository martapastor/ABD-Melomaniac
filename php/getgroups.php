<?php
    // Abrimos la base de datos y guardamos la conexión (no la base de datos entera) en una variable:
    // ('servidor, 'usuario', 'contraseña', 'nombre de la base de datos')
    $db = @mysqli_connect('localhost', 'root', '', 'melomaniac');
    // Utilizamos el carácter @ para no mostrar las alertas generadas por las funciones en caso de error.

    session_start();

    // Guardamos la variable global con el username del usuario actualmente logueado por comodidad:
    $current_user = $_SESSION['username'];

    // Consulta para seleccionar todos los grupos a los que pertenece el usuario actual.
    $sql_get_groups = "SELECT * FROM groups JOIN usergroups ON groups.name=usergroups.groupname WHERE usergroups.username='$current_user' ORDER BY groupname ASC";
    $query_get_groups = mysqli_query($db, $sql_get_groups);
    $object_group = mysqli_fetch_object($query_get_groups);

    $groups = array();

    while($object_group != NULL) {
        $groups[] = html_entity_decode($object_group->name);
        $object_group = mysqli_fetch_object($query_get_groups);
    }

    // Devolvemos un array codificado como JSON con todos los grupos a los que pertenece el usuario:
    echo json_encode($groups);

    // Cerramos la conexión por seguridad:
    @mysqli_close($db);
?>
