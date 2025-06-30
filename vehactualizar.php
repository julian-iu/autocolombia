    <?php

    $id = strtoupper($_POST["id"]);
    $v_marca = strtoupper($_POST["v_marca"]);
    $v_placaviejo = strtoupper($_POST["v_placaviejo"]);
    $v_modelo = strtoupper($_POST["v_modelo"]);
    //$v_placa = strtoupper($_POST["v_placa"]);
    $v_placa = str_replace(' ', '', strtoupper($_POST["v_placa"]));
    $v_color = strtoupper($_POST["v_color"]);
    function validar_identificacion($v_placa, $v_placaviejo)
    {
        if ($v_placa != $v_placaviejo) {
            $cantidad = 1;
        } else {
            $cantidad = 0;
        }
        return $cantidad;
    }
    $validarD = validar_identificacion($v_placa, $v_placaviejo);

    $sqlcantidad = "SELECT COUNT(*) FROM vehiculo WHERE v_placa = '" . $v_placa . "'";
    $query = $pdo->query($sqlcantidad);
    $cantidad = $query->fetchColumn();

    $sql = "UPDATE vehiculo SET v_placa = ?, v_modelo = ?, v_marca = ?, v_color = ? WHERE v_id  = ?;";

    if ($validarD == 1) {

        if ($cantidad != 0) {
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("Información!", "Vehiculo ya registrado con esta PLACA","error");';
            echo '}, 1000);</script>';
            $_SESSION['v_marca'] = $v_marca;
            $_SESSION['v_modelo'] = $v_modelo;
            $_SESSION['v_placa'] = $v_placa;
            $_SESSION['v_color'] = $v_color;
        } else { // insertamos
            $ejecutar = $pdo->prepare($sql);
            $ejecutar->execute(array($v_placa, $v_modelo, $v_marca, $v_color, $id));
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("Información!", "Actualización Exitosa","success");';
            echo '}, 1000);</script>';
            Conexion::desconectar();
        }
    } else {
        $ejecutar = $pdo->prepare($sql);
        $ejecutar->execute(array($v_placaviejo, $v_modelo, $v_marca, $v_color, $id));
        echo '<script type="text/javascript">';
        echo 'setTimeout(function () { swal("Información!", "Actualización Exitosa","success");';
        echo '}, 1000);</script>';
        Conexion::desconectar();
    }
