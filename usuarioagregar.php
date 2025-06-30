<?php

$u_nombre = strtoupper($_POST["u_nombre"]);
$u_celular = strtoupper($_POST["u_celular"]);
$u_usuario = $_POST["u_usuario"];
$u_contrasena = strtoupper($_POST["u_contrasena"]);
$u_tipo = strtoupper($_POST["u_tipo"]);

$sqlcantidad = "SELECT COUNT(*) FROM usuario WHERE u_usuario = '" . $u_usuario . "'";
$query = $pdo->query($sqlcantidad);
$cantidad = $query->fetchColumn();

if ($cantidad != 0) {
    echo '<script type="text/javascript">';
    echo 'setTimeout(function () { swal("Información!", "Usuario ya registrado con este usuario de acceso","error");';
    echo '}, 1000);</script>';
    $_SESSION['u_nombre'] = $u_nombre;
    $_SESSION['u_celular'] = $u_celular;
    $_SESSION['u_usuario'] = $u_usuario;
    $_SESSION['u_contrasena'] = $u_contrasena;
    $_SESSION['u_tipo'] = $u_tipo;

} else { // insertamos
    $sql = "INSERT INTO usuario(u_usuario, u_nombre, u_celular, u_contrasena, u_tipo) VALUES (?, ?, ?, ?, ?);";
    $ejecutar = $pdo->prepare($sql);
    $ejecutar->execute(array($u_usuario, $u_nombre, $u_celular, $u_contrasena, $u_tipo));
    echo '<script type="text/javascript">';
    echo 'setTimeout(function () { swal("Información!", "Registro Exitoso","success");';
    echo '}, 1000);</script>';
    Conexion::desconectar();

    $_SESSION['u_nombre'] = "";
    $_SESSION['u_celular'] =  "";
    $_SESSION['u_usuario'] =  "";
    $_SESSION['u_contrasena'] =  "";
    $_SESSION['u_tipo'] =  "";
}
