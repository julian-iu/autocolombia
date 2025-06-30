    <?php
    $id = strtoupper($_POST["id"]);
    $c_numeroviejo = strtoupper($_POST["c_numeroviejo"]);
    $c_numero = strtoupper($_POST["c_numero"]);

    function validar_identificacion($c_numero, $c_numeroviejo)
    {
        if ($c_numero != $c_numeroviejo) {
            $cantidad = 1;
        } else {
            $cantidad = 0;
        }
        return $cantidad;
    }

    $validarD = validar_identificacion($c_numero, $c_numeroviejo);

    $sqlcantidad = "SELECT COUNT(*) FROM celda WHERE c_numero = '" . $c_numero . "'";
    $query = $pdo->query($sqlcantidad);
    $cantidad = $query->fetchColumn();

    $sql = "UPDATE celda SET c_numero = ? WHERE c_id  = ?;";

    if ($validarD == 1) {

        if ($cantidad != 0) {
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("Información!", "Celda ya Registrada","error");';
            echo '}, 1000);</script>';
        } else { // insertamos
            $ejecutar = $pdo->prepare($sql);
            $ejecutar->execute(array($c_numero, $id));
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("Información!", "Actualización Exitosa","success");';
            echo '}, 1000);</script>';
            Conexion::desconectar();
        }
    } else {
        $ejecutar = $pdo->prepare($sql);
        $ejecutar->execute(array($c_numeroviejo, $id));
        echo '<script type="text/javascript">';
        echo 'setTimeout(function () { swal("Información!", "Actualización Exitosa","success");';
        echo '}, 1000);</script>';
        Conexion::desconectar();
    }
