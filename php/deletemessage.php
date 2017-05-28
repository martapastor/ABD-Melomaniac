<?php
    // Guardamos en variables los datos que nos llegan del formulario POST:
    $id = $_POST['id'];

    // Abrimos la base de datos y guardamos la conexión (no la base de datos entera) en una variable:
    // ('servidor, 'usuario', 'contraseña', 'nombre de la base de datos')
    $db = @mysqli_connect('localhost', 'root', '', 'melomaniac');
    // Utilizamos el carácter @ para no mostrar las alertas generadas por las funciones en caso de error.

    session_start();

    // Guardamos la variable global con el username del usuario actualmente logueado por comodidad:
    $current_user = $_SESSION['username'];

    // Consulta para obtener si los mensajes se han borrado de la bandeja de algunos de los usurios.
    // Si el campo deleted_by_sender está a 1, el mensaje se ha borrado de la bandeja de salida del remitente.
    // Si el campo deleted_by_recipient está a 1, el mensaje se ha borrado de la bandeja de entrada del destinatario.
    $sql_get_message_count = "SELECT * FROM messages WHERE id='$id'";
    $query_get_message_count = mysqli_query($db, $sql_get_message_count);
    $object_message = mysqli_fetch_object($query_get_message_count);

    if ($object_message->deleted_by_sender == 1 || $object_message->deleted_by_recipient == 1) {
        $sql_delete_message = "DELETE FROM messages WHERE id='$id'";
        $query_delete_message = mysqli_query($db, $sql_delete_message);
    } else {
        if ($object_message->sender == $current_user) {
            $sql_deleted_by_sender = "UPDATE messages SET deleted_by_sender=1 WHERE id='$id'";
            $query_deleted_by_sender  = mysqli_query($db, $sql_deleted_by_sender);
        }

        if ($object_message->recipient == $current_user) {
            $sql_deleted_by_recipient = "UPDATE messages SET deleted_by_recipient = 1 WHERE id='$id'";
            $query_deleted_by_recipient  = mysqli_query($db, $sql_deleted_by_recipient);
        }
    }

    // Cerramos la conexión por seguridad, y si necesitamos volver a abrirla para enviar un mensaje, se abre:
    @mysqli_close($db);

    header('Location: ../messages.php');
?>
