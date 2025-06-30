<?php
$v_usuarioFK = strtoupper($_POST["v_usuarioFK"]);
$v_marca = strtoupper($_POST["v_marca"]);
$v_modelo = strtoupper($_POST["v_modelo"]);
$v_color = strtoupper($_POST["v_color"]);
$v_placa = str_replace(' ', '', strtoupper($_POST["v_placa"]));

$sqlcantidad = "SELECT COUNT(*) FROM vehiculo WHERE v_placa = '" . $v_placa . "'";
$query = $pdo->query($sqlcantidad);
$cantidad = $query->fetchColumn();
if ($cantidad != 0) {
    echo '<script type="text/javascript">';
    echo 'setTimeout(function () { swal("Información!", "Vehiculo ya registrado con esta PLACA","error");';
    echo '}, 1000);</script>';
    $_SESSION['v_marca'] = $v_marca;
    $_SESSION['v_modelo'] = $v_modelo;
    $_SESSION['v_placa'] = $v_placa;
    $_SESSION['v_color'] = $v_color;
} else { // insertamos
    $sql = "INSERT INTO vehiculo (v_usuarioFK, v_placa, v_modelo, v_marca, v_color) VALUES (?, ?, ?, ?, ?);";
    $ejecutar = $pdo->prepare($sql);
    $ejecutar->execute(array($v_usuarioFK, $v_placa, $v_modelo, $v_marca, $v_color));
    $_SESSION['l_placa'] = $v_placa;
    echo '<script type="text/javascript">';
    echo 'setTimeout(function () { swal("Información!", "Registro Exitoso","success");';
    echo '}, 1000);</script>';
    Conexion::desconectar();

    $_SESSION['v_marca'] = "";
    $_SESSION['v_modelo'] ="";
    $_SESSION['v_placa'] = "";
    $_SESSION['v_color'] = "";
}

