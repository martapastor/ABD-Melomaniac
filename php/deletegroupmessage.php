<?php
    // Guardamos en variables los datos que nos llegan del formulario POST:
    $id = $_POST['id'];

    // Abrimos la base de datos y guardamos la conexión (no la base de datos entera) en una variable:
    // ('servidor, 'usuario', 'contraseña', 'nombre de la base de datos')
    $db = @mysqli_connect('localhost', 'root', '', 'melomaniac');
    // Utilizamos el carácter @ para no mostrar las alertas generadas por las funciones en caso de error.

    session_start();

    // Consulta para eliminar un mensaje de la tabla groupmessages.
    $sql_delete_message = "DELETE FROM groupmessages WHERE id='$id'";
    $query_delete_message = mysqli_query($db, $sql_delete_message);

    // Cerramos la conexión por seguridad:
    @mysqli_close($db);

    header('Location: ../groups.php');
?>
