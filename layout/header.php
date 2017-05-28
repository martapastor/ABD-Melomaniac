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
        <div class="logout-button">
            ¡Hola, <?php echo $_SESSION['username']?>!
            <a href="php/logout.php">Cerrar sesión</a>
        </div>

        <?php
        // Si un usuario intenta enviar un mensaje pero se produce un error, se muestra una alerta:
        if(isset($_GET['error']) && $_GET['error'] == "unsent_message") { ?>
            <div class = "row">
                <div class = "alert alert-danger">
                        El mensaje no ha podido ser enviado.
                        <br>
                        Por favor, inténtelo de nuevo.
                </div>
            </div>
        <?php } ?>

        <div class = "container-fluid">
            <div class="row">
                <img class = "logo small-logo" alt="Melomaniac Logo" src="img/logo.png">
            </div>
        </div>
    </body>
</html>
