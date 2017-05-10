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
        <!-- Google fonts -->
		<link rel='stylesheet' href='//fonts.googleapis.com/css?family=Abel'>
		<link rel='stylesheet' href='//fonts.googleapis.com/css?family=Actor'>

        <link rel="stylesheet" type="text/css" href="../css/style.css">
        <script src="../js/scripts.js"></script>

        <link rel="icon" type="image/ico" href="../img/favicon.png">
        <title>Melomaniac</title>
    </head>

    <body>
        <div class = "container-fluid">
            <!-- Si al hacer registro o login, no se puede llevar a cabo (los datos introducidos son
            incorrectos en el login o el registro está incompleto), se redirecciona al usuario a esta
            página y muestra una mensaje: -->
            <div class = "row">
                <div <?php if(isset($_GET['error']) && $_GET['error'] == "error_login") {
                    ?> class = "alert alert-warning">
                        El usuario o la contraseña introducidos son incorrectos.
                        <br>
                        Por favor, inténtelo de nuevo.
                    <?php }
                    else {?>
                        >
                        <?php
                    }
                    ?>
                </div>
            </div>

            <div class = "row">
        		<form action="login_submit.php" method="POST" id="form-login">
        			<h2>Welcome to Melomaniac!</h2>
        			<div class="input-group">
        				<span class="input-group-addon"><i class="icon glyphicon glyphicon-user"></i></span>
        				<input type="text" placeholder="Username" name="username" id="username">
        			</div>
        			<br>

        			<div class="input-group">
        				<span class="input-group-addon"><i class="icon glyphicon glyphicon-lock"></i></span>
        				<input type="password" placeholder="Password" name="password" id="password">
        			</div>
        			<br>

        			<input type="submit" value="Log In">
        			<br>
        			<p align="center">Are you new at Melomaniac?<br><a class="signup" href="signup_form.php">Sign Up</a></p>
        		</form>
            </div>
        </div>
    </body>
</html>
