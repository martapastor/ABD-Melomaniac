<?php
    // Guardamos en variables los datos que nos llegan del formulario POST:
    $name = htmlentities($_POST['name']);

    // Abrimos la base de datos y guardamos la conexión (no la base de datos entera) en una variable:
    // ('servidor, 'usuario', 'contraseña', 'nombre de la base de datos')
    $db = @mysqli_connect('localhost', 'root', '', 'melomaniac');
    // Utilizamos el carácter @ para no mostrar las alertas generadas por las funciones en caso de error.

    session_start();

    // Consulta para eliminar el grupo de la tabla groups.
    $sql_delete_group = "DELETE FROM groups WHERE name='$name'";
    $query_delete_group = mysqli_query($db, $sql_delete_group);

    // Como la relación entre las tablas de la base de datos presenta un modelo de relación CASCADE,
    // al eliminar un grupo de la tabla groups, todos los mensajes que iban dirigidos a ese grupo en la tabla
    // groupmessages, así como todas las relaciones de usuarios con ese grupo en la tabla usergroups se
    // eliminan automáticamente también.

    // Cerramos la conexión por seguridad, y si necesitamos volver a abrirla para enviar un mensaje, se abre:
    @mysqli_close($db);

    header('Location: ../admin.php');
?>
