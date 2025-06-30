    <?php
    $id = strtoupper($_POST["id"]);
    $u_nombre = strtoupper($_POST["u_nombre"]);
    $u_celular = strtoupper($_POST["u_celular"]);
    $u_usuarioviejo = $_POST["u_usuarioviejo"];
    $u_usuario = $_POST["u_usuario"];
    $u_contrasena = strtoupper($_POST["u_contrasena"]);
    $u_tipo = strtoupper($_POST["u_tipo"]);

    function validar_identificacion($u_usuario, $u_usuarioviejo)
    {
        if ($u_usuario != $u_usuarioviejo) {
            $cantidad = 1;
        } else {
            $cantidad = 0;
        }
        return $cantidad;
    }

    $validarD = validar_identificacion($u_usuario, $u_usuarioviejo);

    $sqlcantidad = "SELECT COUNT(*) FROM usuario WHERE u_usuario = '" . $u_usuario . "'";
    $query = $pdo->query($sqlcantidad);
    $cantidad = $query->fetchColumn();

    $sql = "UPDATE usuario SET u_usuario = ?, u_nombre = ?, u_celular = ?, u_contrasena = ?, u_tipo = ? WHERE u_id  = ?;";

    if ($validarD == 1) {

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
            $ejecutar = $pdo->prepare($sql);
            $ejecutar->execute(array($u_usuario, $u_nombre, $u_celular, $u_contrasena, $u_tipo, $id));
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("Información!", "Actualización Exitosa","success");';
            echo '}, 1000);</script>';
            Conexion::desconectar();
        }
    } else {
        $ejecutar = $pdo->prepare($sql);
        $ejecutar->execute(array($u_usuarioviejo, $u_nombre, $u_celular, $u_contrasena, $u_tipo, $id));
        echo '<script type="text/javascript">';
        echo 'setTimeout(function () { swal("Información!", "Actualización Exitosa","success");';
        echo '}, 1000);</script>';
        Conexion::desconectar();
    }
