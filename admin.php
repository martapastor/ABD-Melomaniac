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
            if (isset($_SESSION['login']) && $_SESSION['login'] && isset($_SESSION['admin']) && $_SESSION['admin'] == true) {
                require('layout/header.php'); ?>

                <div class="container-fluid">
                    <?php require('layout/sidebar.php'); ?>
                </div>

                <div class="container-fluid">
                    <div class="row">
                        <div class="dark-gray-box">
                            <!-- Modal box for new group-->
                            <button onclick="document.getElementById('modal-new-message').style.display='block'" id="modal-box-button">
                                Nuevo grupo</button>

                            <div id="modal-new-message" class="modal">
                                <form action="php/creategroup.php" method="POST" class="input-form modal-box-content" id="form-login">
                                    <span onclick="document.getElementById('modal-new-message').style.display='none'" class="modal-close-button" title="Close Modal">&times;</span>

                                    <h2>Crear nuevo grupo</h2>
                                    <br>

                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="small-icon fa fa-users"></i></span>
                                        <input type="text" placeholder="Nombre" name="name" id="name">
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

                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="small-icon fa fa-child"></i></span>
                                        <input type="number" placeholder="Edad mínima" name="min_age" id="min_age">
                                    </div>
                                    <br>

                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="small-icon fa fa-blind"></i></span>
                                        <input type="number" placeholder="Edad máxima" name="max_age" id="max_age">
                                    </div>
                                    <br>
                                    <br>

                                    <input type="submit" value="Crear">
                                </form>
                            </div>
                            <!-- End of modal box -->

                            <h2 class="pink-title">Lista de grupos</h2>
                            <?php
                            // Consulta para mostrar todos los grupos que existen en la base de datos.
                            // Un grupo existe si hay una fila creada para el mismo en la tabla groups.
                            $sql_show_groups = "SELECT * FROM groups ORDER BY name ASC";
                            $query_show_groups = mysqli_query($db, $sql_show_groups);
                            $object_groups = mysqli_fetch_object($query_show_groups);

                            if ($object_groups == null) {?>
                                <div class="light-gray-box">
                                    No hay grupos para mostrar.
                                </div>
                            <?php }

                            while ($object_groups != NULL) { ?>
                                <div class="light-gray-box">
                                    Nombre: <?php echo $object_groups->name ?>
                                    <br>
                                    Tipo de música: <?php echo $object_groups->music ?>
                                    <br>
                                    Rango de edades: <?php echo $object_groups->min_age ?> - <?php echo $object_groups->max_age ?>
                                    <br>
                                    <br>
                                    <?php
                                    if (isset($_SESSION['login']) && $_SESSION['login'] && isset($_SESSION['admin']) && $_SESSION['admin']) {
                                        ?>
                                        <form action="php/deletegroup.php" method="POST" id="get-method">
                                            <input type="hidden" id="group-name" name="name" value="<?php echo $object_groups->name ?>" />
                                            <input type="submit" class="php-button" id="delete-group-button" value="Eliminar grupo"/>
                                        </form>
                                    <?php } ?>
                                </div>

                                <?php
                                $object_groups = mysqli_fetch_object($query_show_groups);
                            } ?>
                        </div>
                    </div>
                </div>
            <?php }
            else {
                header('Location: index.php');
            }?>
        </div>

        <?php
        // Cerramos la conexión por seguridad, y si necesitamos volver a abrirla para enviar un mensaje, se abre:
        @mysqli_close($db);
        ?>
    </body>
</html>
