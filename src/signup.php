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
                <div <?php if(isset($_GET['error']) && $_GET['error'] == "error_signup") {
                    ?> class = "alert alert-warning">
                        Alguno de los datos introducidos es incorrecto. Por favor, inténtelo de nuevo.
                    <?php }
                    else {?>
                        >
                        <?php
                    }
                    ?>
                </div>
            </div>

            <div class = "row">
        		<form action="queries/signup.php" method="POST" id="form-signup">
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
                      <span class="input-group-addon"><i class="small-icon glyphicon glyphicon-calendar"></i></span>
                      <input type="date" onfocus="(this.type='date')" name="age" id="age">
                    </div>
                    <br>

                    <div class="input-group">
                      <span class="input-group-addon"><i class="small-icon glyphicon glyphicon-lock"></i></span>
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
                    <br>
                    <p align="center">Do you already have an account?<br><a class="login" href="login.php">Log In</a></p>
                </form>
            </div>
        </div>

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
