<?php
 session_start(); // allows us to retrieve our key form the session

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $usuario = $_POST['usuario'];
        $contrasena =$_POST['contrasena'];
        $sql = "SELECT COUNT(*) FROM usuario WHERE u_usuario = '$usuario' AND u_contrasena  = '$contrasena'";
        $query = $pdo->query($sql);
        $cantidad = $query->fetchColumn();

        if ($cantidad == 0) {
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("No Autorizado!", "Usuario y/o Contraseña Incorrectas","error");';
            echo '}, 1000);</script>';
        } else {
            $sql = "SELECT * FROM usuario WHERE u_usuario = ? AND u_contrasena = ? ";
            $ejecutar = $pdo->prepare($sql);
            $ejecutar->execute(array($usuario, $contrasena));
            $dato = $ejecutar->fetch(PDO::FETCH_ASSOC);
                $_SESSION['permitido'] = 'SI';
                $_SESSION['usuario'] = $dato['u_id'];
                header("Location: dashboard");

                //header("Location: dashboard2");
                Conexion::desconectar();
        }
    }

?>