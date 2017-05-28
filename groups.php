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
            $db = @mysqli_connect('localhost','root','','melomaniac');

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
                            <button onclick="document.getElementById('modal-new-message').style.display='block'" id="new-message-button">
                                Nuevo mensaje</button>

                            <div id="modal-new-message" class="modal">
                                <form action="php/sendgroupmessage.php" method="POST" class="input-form modal-box-content" id="form-new-message">
                                    <span onclick="document.getElementById('modal-new-message').style.display='none'" class="modal-close-button" title="Close Modal">&times;</span>

                                    <h2>Enviar mensaje grupal</h2>
                                    <br>

                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="small-icon glyphicon glyphicon-user"></i></span>
                                        <input type="text" value="<?php echo $_SESSION['username'] ?>" name="sender" id="sender" class="input-disabled" disabled>
                                    </div>
                                    <br>

                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="small-icon fa fa-users"></i></span>
                                        <input type="text" placeholder="Grupo" name="recipient" id="group-recipient" class="ui-autocomplete-input">
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
                            <!-- End of modal box -->

                            <?php
                            // Si el usuario quiere mostrar los mensajes de un grupo, la petición GET le redirecciona:
                            if(isset($_GET['group'])) { ?>
                                <h2 class="pink-title">Mensajes en <?php echo $_GET['group'] ?> </h2>
                                <?php
                                // Guardamos la variable global con el username del usuario actualmente logueado
                                // y del grupo del que queremos ver los mensajes por comodidad:
                                $current_user = $_SESSION['username'];
                                $current_group = htmlentities($_GET['group']);

                                // Consulta para mostrar todos los mensajes de un grupo concreto de la base de datos.
                                // Un mensaje se considera de un grupo concreto cuando el campo group_recipient de la tabla groupmessages
                                // coincide con el nombre del grupo actual que hemos guardado en la variable &current_group.
                                $sql_get_messages = "SELECT * FROM groupmessages JOIN usergroups ON groupmessages.group_recipient=usergroups.groupname
                                WHERE usergroups.username='$current_user' AND usergroups.groupname='$current_group'ORDER BY id DESC";
                                $query_get_messages = mysqli_query($db, $sql_get_messages);
                                $object_message = mysqli_fetch_object($query_get_messages);

                                if ($object_message == null) {?>
                                    <div class="light-gray-box">
                                        No hay mensajes para mostrar.
                                    </div>
                                <?php }

                                while ($object_message != null) {?>
                                    <div class="light-gray-box">
                                        <?php
                                        // Consulta para mostar el remitente del mensaje con el nombre completo, no con el username.
                                        // Empleando el username del campo sender de la tabla messages, obtenemos el nombre completado
                                        // del campo name de la tabla users.
                                        $sql_get_complete_name = "SELECT name FROM users WHERE username='$object_message->sender'";
                                        $query_get_complete_name = mysqli_query($db, $sql_get_complete_name);
                                        $object_sender = mysqli_fetch_object($query_get_complete_name); ?>

                                        Remitente: <?php echo $object_sender->name ?>
                                        <br>
                                        Fecha: <?php echo $object_message->id ?>
                                        <br>
                                        <br>
                                        <span class="message-subject"> Asunto: <?php echo $object_message->subject ?> </span>
                                        <br>
                                        <br>
                                        <span class="message-body"> <?php echo html_entity_decode($object_message->body) ?></span>
                                        <br>
                                        <br>
                                        <?php
                                        if (isset($_SESSION['login']) && $_SESSION['login'] && $_SESSION['username'] == $object_message->sender) {
                                            // Empleamos un formulario para enviar vía POST el id del mensaje en concreto que queremos borrar.
                                            // Necesitamos hacerlo de esta forma ya que al cargar los mensajes con un loop while, necesitas que el
                                            // id de cada mensaje se guarde en una variable para poder luego borrarlo al hacer click en el botón.
                                            // Si empleáramos un #id para guardar esa información, sólo se nos guardaría del último mensaje cargado,
                                            // ya que los #id deben ser únicos en cada página. ?>
                                            <form action="php/deletegroupmessage.php" method="POST" id="get-method">
                                                <input type="hidden" id="message-id" name="id" value="<?php echo $object_message->id ?>" />
                                                <input type="submit" class="php-button" id="delete-groupmessages-button" value="Eliminar mensaje"/>
                                            </form>
                                        <?php } ?>
                                    </div>
                                    <?php
                                    $object_message = mysqli_fetch_object($query_get_messages);
                                }

                                // Empleamos un formulario para recargar vía POST la vista principal con todos los grupos. ?>
                                <form action="groups.php" method="POST">
                                    <input type="submit" class="php-button" value="Volver a grupos"/>
                                </form>
                            <?php } else { ?>
                                <h2 class="pink-title">Mis grupos</h2>
                                <?php
                                // Guardamos la variable global con el username del usuario actualmente logueado por comodidad:
                                $current_user = $_SESSION['username'];

                                // Consulta para mostrar todos los grupos a los que pertenece el usuario de la base de datos.
                                // Un usuario se considera que pertenece a un grupo groupname cuando el campo username de la tabla usergroups
                                // coincide con el username del usuario actualmente logueado.
                                $sql_show_groups = "SELECT * FROM groups JOIN usergroups ON groups.name=usergroups.groupname WHERE usergroups.username='$current_user' ORDER BY groupname ASC";
                                $query_show_groups = mysqli_query($db, $sql_show_groups);
                                $object_groups = mysqli_fetch_object($query_show_groups);

                                if ($object_groups == NULL) {?>
                                    <div class="light-gray-box">
                                        No hay grupos para mostrar.
                                    </div>
                                <?php }

                                while ($object_groups != NULL) {?>
                                    <div class="light-gray-box">
                                        Nombre: <?php echo $object_groups->name ?>
                                        <br>
                                        Tipo de música: <?php echo $object_groups->music ?>
                                        <br>
                                        Rango de edades: <?php echo $object_groups->min_age ?> - <?php echo $object_groups->max_age ?>
                                        <br>
                                        <br>

                                        <form action="groups.php" method="GET" id="get-method">
                                            <input type="hidden" id="show-message-id" name="group" value="<?php echo $object_groups->name ?>" />
                                            <input type="submit" class="php-button" id="show-groupmessages-button" value="Ver mensajes"/>
                                        </form>
                                    </div>
                                    <br>
                                    <?php
                                    $object_groups = mysqli_fetch_object($query_show_groups);
                                }
                            } ?>
                        </div>
                    </div>
                </div>
            <?php } else {
                header('Location: index.php');
            } ?>
        </div>

        <?php
        // Cerramos la conexión por seguridad:
        @mysqli_close($db);
        ?>
    </body>
</html>
