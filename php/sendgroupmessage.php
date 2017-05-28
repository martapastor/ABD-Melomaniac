<?php
    // Guardamos en variables los datos que nos llegan del formulario POST:
    $recipient = htmlentities($_POST['recipient']);
    $subject = nl2br(htmlentities($_POST['subject'], ENT_QUOTES, UTF-8));
    $message = nl2br(htmlentities($_POST['message'], ENT_QUOTES, UTF-8));

    // Abrimos la base de datos y guardamos la conexión (no la base de datos entera) en una variable:
    // ('servidor, 'usuario', 'contraseña', 'nombre de la base de datos')
    $db = @mysqli_connect('localhost', 'root', '', 'melomaniac');
    // Utilizamos el carácter @ para no mostrar las alertas generadas por las funciones en caso de error.

    session_start();

    // Consulta para añadir un mensaje a la tabla groupmessages.
    // Esta tabla guarda los mensajes grupales, con el campo group_recipient siempre con un nombre de
    // un grupo que exista en la base de datos.
    $sql_send_message = "INSERT INTO groupmessages(id, subject, body, sender, group_recipient) VALUES (CURRENT_TIMESTAMP, '$subject', '$message', '$_SESSION[username]', '$recipient')";
    $query_send_message = mysqli_query($db, $sql_send_message);

    // Si se ha enviado correctamente, se nos redirige al tablón de anuncios y se muestra el nuevo mensaje.
    // En caso de error, devuelve un error de tipo unsent_message.
    if ($query_send_message != null) {
        header('Location: ../groups.php');
    } else {
        header('Location: ../groups.php?error=unsent_message');
    }

    // Cerramos la conexión por seguridad:
    @mysqli_close($db);
?>
