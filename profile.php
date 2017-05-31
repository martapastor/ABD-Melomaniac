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
                    <div class = "row">
                        <div class = "dark-gray-box">
                            <?php /*
                            <!-- Modal box for edit profile-->
                            <button onclick="document.getElementById('modal-edit-profile').style.display='block'" id="modal-box-button">
                                Editar</button>

                            <div id="modal-edit-profile" class="modal">
                                <form action="php/updateprofile.php" method="POST" class="input-form modal-box-content" id="edit-profile">
                                    <span onclick="document.getElementById('modal-edit-profile').style.display='none'" class="modal-close-button" title="Close Modal">&times;</span>

                                    <h2>Editar mi perfil</h2>
                                    <br>

                                    <div class="input-group">
                                      <span class="input-group-addon"><i class="small-icon glyphicon glyphicon-envelope"></i></span>
                                      <input type="text" placeholder="Email" name="email" id="email">
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

                                    <input type="submit" value="Guardar cambios">
                                </form>
                            </div>
                            <!-- End of modal box -->
                            */ ?>

                            <h2 class="pink-title">Mi perfil</h2>
                            <?php
                            // Guardamos la variable global con el username del usuario actualmente logueado por comodidad:
                            $current_user = $_SESSION['username'];

                            // Consulta para mostrar todos los mensajes públicos de la base de datos.
                            // Un mensaje se considera público si en la tabla messages, el campo recipient está vacío.
                            $sql_show_profile_info = "SELECT * FROM users WHERE username='$current_user'";
                            $query_show_profile_info = mysqli_query($db, $sql_show_profile_info);
                            $object_user = mysqli_fetch_object($query_show_profile_info); ?>

                            <div class="light-gray-box">
                                Nombre completo: <?php echo $object_user->name ?>
                                <br>
                                Tipo de música: <?php echo $object_user->music ?>
                                <br>
                                <br>
                                Nombre de usuario: <?php echo $object_user->username ?>
                                <br>
                                Correo electrónico: <?php echo $object_user->email ?>
                                <br>
                                <br>
                                Fecha de nacimiento: <?php echo $object_user->age ?>
                                <br>
                                Edad: <?php
                                    $age_timestamp = strtotime($object_user->age);
                                    $current_date_timestamp = strtotime(date("Y-m-d"));
                                    $current_age = floor(($current_date_timestamp - $age_timestamp) / 60 / 60 / 24 / 365.25);
                                    echo $current_age; ?>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <?php
        // Cerramos la conexión por seguridad, y si necesitamos volver a abrirla para enviar un mensaje, se abre:
        @mysqli_close($db);
        ?>
    </body>
</html>
