<?php
include_once 'configuracion/conexion.php';
$pdo = Conexion::conectar();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once 'login.php';
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script type="text/javascript" src="js/jquery-3.6.0.min.js"></script>
    <link rel="shortcut icon" href="imagen/icono.png">
    <script src="jquery/jquery-ui.js" type="text/javascript"></script>
    <link href="jquery/jquery-ui.min.css" rel="stylesheet" type="text/css" />
    <link href="fontawesome/css/all.css" rel="stylesheet" />
    <script src="fontawesome/js/all.js" crossorigin="anonymous"></script>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/estilos.css" rel="stylesheet" />
    <script src="js/sweetalert.min.js" type="text/javascript"></script>
    <title>AutoColombia</title>
</head>

<body>
    <br><br><br><br> <br><br>

    <div class="container">
        <center>
            <div style="width: 20rem; text-align: center">
                <img src="imagen/logo.png" class="card-img-top img-fluid" style="width: 50%;" class="img-fluid">
                <div class="card-body">
                  <form ACTION="" method="POST">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                            <input type="text" name="usuario" class="form-control" placeholder="Usuario" required />
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" class="form-control" name="contrasena" placeholder="Contraseña" required />
                        </div>
                        <center><button type="submit" class="btn btn-success btn-sm">Login &nbsp;&nbsp; <i class="fa-solid fa-lock-open"></i></button></center>
                    </form>
                </div>
            </div>
        </center>

    </div>
</body>

</html>
<style type="text/css">
    .btn-primary {
        background-color: #1388cd;
    }

    .btn-dark {
        background-color: #5e5e5e;

    }

    .card-header {
        background-color: #04c5fe;

    }
</style>