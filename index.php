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

                                    <h2>Enviar mensaje público</h2>
                                    <br>

                                    <div class="input-group">
                                        <input type="hidden" value="" name="recipient" id="recipient">
                                    </div>

                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="small-icon glyphicon glyphicon-user"></i></span>
                                        <input type="text" value="<?php echo $_SESSION['username'] ?>" name="sender" id="sender" class="input-disabled" disabled>
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

                            <h2 class="pink-title">Tablón de anuncios</h2>
                            <?php
                            // Consulta para mostrar todos los mensajes públicos de la base de datos.
                            // Un mensaje se considera público si en la tabla messages, el campo recipient está vacío.
                            $sql_show_public_messages = "SELECT * FROM messages WHERE recipient='' ORDER BY id DESC";
                            $query_show_public_messages = mysqli_query($db, $sql_show_public_messages);
                            $object_messages = mysqli_fetch_object($query_show_public_messages);

                            if ($object_messages == NULL) {?>
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
                                    <span class = "message-subject"> Asunto: <?php echo $object_messages->subject ?> </span>
                                    <br>
                                    <br>
                                    <span class = "message-body"> <?php echo html_entity_decode($object_messages->body) ?></span>
                                    <br>
                                    <br>
                                    <?php
                                    if (isset($_SESSION['login']) && $_SESSION['login'] && $_SESSION['username'] == $object_messages->sender) {
                                        ?>
                                        <form action="php/deletemessage.php" method="POST" id="get-method">
                                            <input type="hidden" id="message-id" name="id" value="<?php echo $object_messages->id ?>" />
                                            <input type="submit" class="php-button" id="delete-message-button" value="Eliminar mensaje"/>
                                        </form>
                                    <?php } ?>
                                </div>

                                <?php
                                $object_messages = mysqli_fetch_object($query_show_public_messages);
                            } ?>
                        </div>
                    </div>
                </div>
            <?php }
            // Si ningún usuario ha iniciado sesión, se muestra la página para loguearse o registrarse:
            else {
                // Si al hacer registro o login, no se puede llevar a cabo (los datos introducidos son
                // incorrectos en el login o el registro está incompleto), se redirecciona al usuario a esta
                // página y muestra una mensaje:
                if(isset($_GET['error']) && $_GET['error'] == "error_login") { ?>
                   <div class = "row">
                       <div class = "alert alert-warning">
                           El usuario o la contraseña introducidos son incorrectos.
                           <br>
                           Por favor, inténtelo de nuevo.
                       </div>
                   </div>
                <?php }

                // Si al intentar registrar un nuevo usuario, el username ya existe en la base de datos,
                // se redirecciona al usuario a esta página y se le muestra un mensaje:
                if(isset($_GET['error']) && $_GET['error'] == "username_exist") { ?>
                   <div class = "row">
                       <div class = "alert alert-danger">
                           El nombre de usuario ya existe.
                           <br>
                           Por favor, escoja otro nombre.
                       </div>
                   </div>
                <?php } ?>

                <div class="row">
                    <img class = "logo" alt="Melomaniac Logo" src="img/logo.png">
                </div>

                <div class="row">
        			<button onclick="document.getElementById('modal-login').style.display='block'" class="col-md-4 index-button">Login</button>
        			<button onclick="document.getElementById('modal-signup').style.display='block'" class="col-md-4 index-button">Register</button>

                    <div id="modal-login" class="modal">
                        <form action="php/login.php" method="POST" class="input-form modal-box-content" id="form-login">
                            <span onclick="document.getElementById('modal-login').style.display='none'" class="modal-close-button" title="Close Modal">&times;</span>

                            <h2>Welcome to Melomaniac!</h2>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="small-icon glyphicon glyphicon-user"></i></span>
                                <input type="text" placeholder="Username" name="username" id="username">
                            </div>
                            <br>

                            <div class="input-group">
                                <span class="input-group-addon"><i class="small-icon glyphicon glyphicon-lock"></i></span>
                                <input type="password" placeholder="Password" name="password" id="password">
                            </div>
                            <br>

                            <input type="submit" value="Log In">
                        </form>
                    </div>

                    <div id="modal-signup" class="modal">
                        <form action="php/signup.php" method="POST" onsubmit="return validateSignupForm();" class="input-form modal-box-content" id="form-signup">
                            <span onclick="document.getElementById('modal-signup').style.display='none'" class="modal-close-button" title="Close Modal">&times;</span>

                            <h2>Welcome to Melomaniac!</h2>

                            <div class="input-group">
                              <span class="input-group-addon"><i class="small-icon glyphicon glyphicon-font"></i></span>
                              <input type="text" placeholder="Name" name="name" id="name">
                            </div>
                            <br>

                            <div class="input-group">
                              <span class="input-group-addon"><i class="small-icon glyphicon glyphicon-envelope"></i></span>
                              <input type="text" placeholder="Email" name="email" id="email">
                            </div>
                            <br>

                            <div class="input-group">
                              <span class="input-group-addon"><i class="small-icon glyphicon glyphicon-user"></i></span>
                              <input type="text" placeholder="Username" name="username" id="username">
                            </div>
                            <br>

                            <div class="input-group">
                              <span class="input-group-addon"><i class="small-icon glyphicon glyphicon-lock"></i></span>
                              <input type="password" placeholder="Password" name="password" id="password">
                            </div>
                            <br>

                            <div class="input-group">
                              <span class="input-group-addon"><i class="small-icon glyphicon glyphicon-lock"></i></span>
                              <input type="password" placeholder="Confirm password" name="confirmpassword" id="confirmpassword">
                            </div>
                            <br>

                            <div class="input-group">
                              <span class="input-group-addon"><i class="small-icon fa fa-birthday-cake"></i></span>
                              <input type="date" min="1900-01-01" max="2003-12-31" name="age" id="age">
                            </div>
                            <br>

                            <div class="input-group">
                              <span class="input-group-addon"><i class="small-icon glyphicon glyphicon-music"></i></span>
                              <select name="music">
                                  <option value="rock">Rock</option>
                                  <option value="pop">Pop</option>
                                  <option value="house">House</option>
                                  <option value="indie">Indie</option>
                                  <option value="metal">Metal</option>
                                  <option value="reggae">Reggae</option>
                                  <option value="classical">Clásica</option>
                                  <option value="rap">Rap</option>
                              </select>
                            </div>
                            <br>

                            <input type="submit" value="Sign Up">
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>

        <?php
        // Cerramos la conexión por seguridad:
        @mysqli_close($db); ?>
    </body>

    <script type="application/javascript">
		validemail = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		document.getElementById('email').addEventListener("change", function () {
			if (!validemail.test(this.value)) {
				document.getElementById('email').style.border = "2px solid red";
			} else {
				document.getElementById('email').style.border = "2px solid green";
			}
		}, false);
	</script>
</html>
