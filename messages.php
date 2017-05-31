<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1">

        <!-- jQuery library -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <!-- Bootstrap library -->
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!-- W3Schools library -->
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Google fonts -->
		<link rel='stylesheet' href='//fonts.googleapis.com/css?family=Abel'>
		<link rel='stylesheet' href='//fonts.googleapis.com/css?family=Actor'>

        <link rel="stylesheet" type="text/css" href="css/style.css">
        <script src="js/scripts.js"></script>

        <link rel="icon" type="image/ico" href="img/favicon.png">
        <title>Melomaniac</title>
    </head>

    <body>
        <div class="container-fluid">
            <?php
            // Iniciamos la conexión con la base de datos:
            $db = @mysqli_connect('localhost','melomaniac','melomaniac','melomaniac');

            // Establecemos el cotejamiento como el de la base de datos para que muestre correctamente
            // los caracteres acentuados:
            @mysqli_set_charset($db, 'utf8');
            // Utilizamos el carácter @ para no mostrar las alertas generadas por las funciones en caso de error.

            session_start();

            // Si un usuario ha iniciado sesión, se le muestra la página principal:
            if (isset($_SESSION['login']) && $_SESSION['login']) {
                require('layout/header.php'); ?>

                <div class="container-fluid">
                    <?php require('layout/sidebar.php'); ?>
                </div>

                <div class="container-fluid">
                    <div class="row">
                        <div class="dark-gray-box">
                            <!-- Modal box for new message-->
                            <button onclick="document.getElementById('modal-new-message').style.display='block'" id="modal-box-button">
                                Nuevo mensaje</button>

                            <div id="modal-new-message" class="modal">
                                <form action="php/sendmessage.php" method="POST" class="input-form modal-box-content" id="form-new-message">
                                    <span onclick="document.getElementById('modal-new-message').style.display='none'" class="modal-close-button" title="Close Modal">&times;</span>

                                    <h2>Enviar mensaje privado</h2>
                                    <br>

                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="small-icon glyphicon glyphicon-user"></i></span>
                                        <input type="text" value="<?php echo $_SESSION['username'] ?>" name="sender" id="sender" class="input-disabled" disabled>
                                    </div>
                                    <br>

                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="small-icon glyphicon glyphicon-user"></i></span>
                                        <input type="text" placeholder="Destinatario" name="recipient" id="recipient" class="ui-autocomplete-input">
                                    </div>
                                    <br>

                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="small-icon glyphicon glyphicon-envelope"></i></span>
                                        <input type="text" placeholder="Asunto" name="subject" id="subject">
                                    </div>
                                    <br>

                                    <textarea name="message" id="message" maxlength="600" rows="10" cols="40" required=""></textarea>
                                    <br>
                                    <br>

                                    <input type="submit" value="Enviar">
                                </form>
                            </div>
                            <!-- End of modal box-->

                            <?php
                            // Si estamos en la página general de mensajes, se muestran dos botones para ir a la bandeja
                            // de entrada o de elementos enviados. A esta página sólo se puede llegar introduciendo la dirección
                            // exacta en la barra del navegador, pero por si acaso mostramos los botones para redireccionar.
                            if(!isset($_GET['type'])) { ?>
                                <h2 class="pink-title">Mis mensajes personales</h2>

                                <div class="light-gray-box">
                                    <form action="messages.php?type=received" method="POST">
                                        <input type="submit" class="php-button" id="show-groupmessages-button" value="Ver mensajes recibidos"/>
                                    </form>
                                </div>

                                <div class="light-gray-box">
                                    <form action="messages.php?type=sent" method="POST">
                                        <input type="submit" class="php-button" id="show-groupmessages-button" value="Ver mensajes enviados"/>
                                    </form>
                                </div>

                            <?php }

                            // Si el usuario quiere mostrar la bandeja de entrada de mensajes, la petición GET le redirecciona:
                            if(isset($_GET['type']) && $_GET['type'] === "received") { ?>
                                <h2 class="pink-title">Mensajes recibidos</h2>

                                <?php
                                // Guardamos la variable global con el username del usuario actualmente logueado por comodidad:
                                $current_user = $_SESSION['username'];

                                // Consulta para mostrar todos los mensajes personales enviados de la base de datos.
                                // Un mensaje se considera personal y enviado por un usuario cuando el campo recipient de la tabla messages
                                // coincide con el username del usuario actualmente logueado.
                                $sql_show_received_messages = "SELECT * FROM messages WHERE recipient='$current_user' AND deleted_by_recipient=0 ORDER BY id DESC";
                                $query_show_received_messages = mysqli_query($db, $sql_show_received_messages);
                                $object_messages = mysqli_fetch_object($query_show_received_messages);

                                if ($object_messages == NULL) { ?>
                                    <div class="light-gray-box">
                                        No hay mensajes para mostrar.
                                    </div>
                                <?php }

                                while ($object_messages != NULL) { ?>
                                    <div class="light-gray-box">
                                        <?php
                                        // Consulta para mostar el remitente del mensaje con el nombre completo, no con el username.
                                        // Empleando el username del campo sender de la tabla messages, obtenemos el nombre completado
                                        // del campo name de la tabla users.
                                        $sql_get_complete_name = "SELECT name FROM users WHERE username='$object_messages->sender'";
                                        $query_get_complete_name = mysqli_query($db, $sql_get_complete_name);
                                        $object_sender = mysqli_fetch_object($query_get_complete_name); ?>

                                        Remitente: <?php echo $object_sender->name ?>
                                        <br>
                                        Fecha: <?php echo $object_messages->id ?>
                                        <br>
                                        <br>
                                        <span class="message-subject"> Asunto: <?php echo $object_messages->subject ?> </span>
                                        <br>
                                        <br>
                                        <span class="message-body"> <?php echo $object_messages->body ?></span>
                                        <br>
                                        <br>
                                        <?php
                                        if (isset($_SESSION['login']) && $_SESSION['login'] && $current_user == $object_messages->recipient) { ?>
                                            <form action="php/deletemessage.php" method="POST">
                                                <input type="hidden" id="message-id" name="id" value="<?php echo $object_messages->id ?>" />
                                                <input type="submit" class="php-button" id="delete-message-button" value="Eliminar mensaje"/>
                                            </form>
                                        <?php } ?>
                                    </div>

                                    <?php
                                    $object_messages = mysqli_fetch_object($query_show_received_messages);
                                }
                            }

                            // Si el usuario quiere mostrar los mensajes que ha enviado, la petición GET le redirecciona:
                            if(isset($_GET['type']) && $_GET['type'] === "sent") { ?>
                                <h2 class="pink-title">Mensajes enviados</h2>
                                <?php
                                // Guardamos la variable global con el username del usuario actualmente logueado por comodidad:
                                $current_user = $_SESSION['username'];

                                // Consulta para mostrar todos los mensajes personales enviados de la base de datos.
                                // Un mensaje se considera personal y enviado por un usuario cuando el campo recipient de la tabla messages
                                // coincide con el username del usuario actualmente logueado.
                                $sql_show_sent_messages = "SELECT * FROM messages WHERE sender='$current_user' AND recipient!='' AND deleted_by_sender=0 ORDER BY id DESC";
                                $query_show_sent_messages = mysqli_query($db, $sql_show_sent_messages);
                                $object_messages = mysqli_fetch_object($query_show_sent_messages);

                                if ($object_messages == NULL) { ?>
                                    <div class="light-gray-box">
                                        No hay mensajes para mostrar.
                                    </div>
                                <?php }

                                while ($object_messages != NULL) { ?>
                                    <div class="light-gray-box">
                                        <?php
                                        // Consulta para mostar el remitente del mensaje con el nombre completo, no con el username.
                                        // Empleando el username del campo sender de la tabla messages, obtenemos el nombre completado
                                        // del campo name de la tabla users.
                                        $sql_get_complete_name = "SELECT name FROM users WHERE username='$object_messages->recipient'";
                                        $query_get_complete_name = mysqli_query($db, $sql_get_complete_name);
                                        $object_recipient = mysqli_fetch_object($query_get_complete_name); ?>

                                        Destinatario: <?php echo $object_recipient->name ?>
                                        <br>
                                        Fecha: <?php echo $object_messages->id ?>
                                        <br>
                                        <br>
                                        <span class="message-subject"> Asunto: <?php echo $object_messages->subject ?> </span>
                                        <br>
                                        <br>
                                        <span class="message-body"> <?php echo $object_messages->body ?></span>
                                        <br>
                                        <br>
                                        <?php
                                        if (isset($_SESSION['login']) && $_SESSION['login'] && $current_user == $object_messages->sender) { ?>
                                            <form action="php/deletemessage.php" method="POST" id="get-method">
                                                <input type="hidden" id="message-id" name="id" value="<?php echo $object_messages->id ?>" />
                                                <input type="submit" class="php-button" id="delete-message-button" value="Eliminar mensaje"/>
                                            </form>
                                        <?php } ?>
                                    </div>

                                    <?php
                                    $object_messages = mysqli_fetch_object($query_show_sent_messages);
                                }
                            } ?>
                        </div>
                    </div>
                </div>
            <?php
            } else {
                header('Location: index.php');
            } ?>
        </div>

        <?php
        // Cerramos la conexión por seguridad:
        @mysqli_close($db); ?>
    </body>
</html>
