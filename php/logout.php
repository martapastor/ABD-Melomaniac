<?php
	session_start();

	// Cerramos la sesión, y para mayor seguridad, borramos las variables globales con los datos
	// de la sesión del usuario:
    session_destroy();
	unset($_SESSION["login"]);
    unset($_SESSION["username"]);
	unset($_SESSION["name"]);

	$_SESSION=[];
	@mysqli_close($db);

	// Se nos redirige a la página de inicio para volver a loguearnos o registrarnos:
	header('Location: ../')
?>
