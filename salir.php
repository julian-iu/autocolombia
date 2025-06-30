<?php ob_start(); ?> 
<?php

session_start(); //Inicializar la sesi�n
$_SESSION = array();  //Destruir todas las variables de sesi�n que hallan sido inicializadas.
session_destroy(); //Se destruye la sesi�n actual.
header('Location: index.php/..');
?>
<?php ob_end_flush(); ?> 