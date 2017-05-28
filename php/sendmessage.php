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

    // Si el destinatario es todo el mundo (es decir, el campo recipient está vacío)
    // la cuenta se pone a 1, es decir, se marca como si el destinatario del mensaje quisiera borrarlo,
    // ya que en los mensajes públicos, en cuando el remitente decide borrarlo, deben borrarse de la base de datos.
    // En caso de ser un mensaje personal a otro usuario (es decir, el campo recipient está completo)
    // la cuenta se pone a 0, ya que son dos usuarios distintos los que reciben el mensaje personal,
    // y el hecho de que uno lo borre de su bandeja no implica que también tenga que borrarse en la del otro.
    if($recipient == "") {
        $deleted_by_recipient = 1;
    } else {
        $deleted_by_recipient = 0;
    }

    // Consulta para añadir un mensaje a la tabla messages.
    // Esta tabla guarda los mensajes personales (con destinatario en el campo recipiento) como los
    // mensajes públicos (con el campo recipient vacío).
    $sql_send_message = "INSERT INTO messages(id, subject, body, sender, recipient, deleted_by_recipient) VALUES (CURRENT_TIMESTAMP, '$subject', '$message', '$_SESSION[username]', '$recipient', '$deleted_by_recipient')";
    $query_send_message = mysqli_query($db, $sql_send_message);

    // Si se ha enviado correctamente, se nos redirige al tablón de anuncios y se muestra el nuevo mensaje.
    // En caso de error, devuelve un error de tipo unsent_message.
    if ($query_send_message != NULL) {
        if($recipient == "") {
            header('Location: ../index.php');
        } else {
            header('Location: ../messages.php?type=sent');
        }
    } else {
        header('Location: ../messages.php?type=sent&error=unsent_message');
    }

    // Cerramos la conexión por seguridad:
    @mysqli_close($db);
?>
