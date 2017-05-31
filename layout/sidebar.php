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
        <div class="w3-sidebar w3-bar-block">
            <a href="index.php" class="w3-bar-item w3-button">Inicio</a>
            <br>

            <div class="w3-dropdown-hover">
                <button class="w3-button">Mis mensajes<i class="fa fa-chevron-down"></i></button>

                <div class="w3-dropdown-content w3-bar-block">
                  <a href="messages.php?type=received" class="w3-bar-item w3-button">Recibidos</a>
                  <a href="messages.php?type=sent" class="w3-bar-item w3-button">Enviados</a>
                </div>
            </div>
            <br>
            <br>

            <a href="groups.php" class="w3-bar-item w3-button">Mis grupos</a>
            <br>
            
            <a href="profile.php" class="w3-bar-item w3-button">Mi perfil</a>
            <br>
            <br>

            <?php
            if (isset($_SESSION['login']) && $_SESSION['login'] && isset($_SESSION['admin']) && $_SESSION['admin']){ ?>
                <a href="admin.php" class="w3-bar-item w3-button">Administración</a>
            <?php } ?>
        </div>
    </body>
</html>
