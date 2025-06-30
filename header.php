<?php
include_once 'configuracion/conexion.php';
include_once('configuracion/sesion.php');
$pdo = Conexion::conectar();

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
date_default_timezone_set('America/Bogota');
$horasistema = date("H:i:s");
$fechasistema = date("Y-m-d");
$anosistema = date("Y");
$messistema = date("m");

$idsesion = $_SESSION["usuario"];
$buscarinfo = 'SELECT * FROM usuario WHERE u_id = ?';
$perq = $pdo->prepare($buscarinfo);
$perq->execute(array($idsesion));
$datousuario = $perq->fetch(PDO::FETCH_ASSOC);
$u_id = $datousuario['u_id'];
$u_nombre = $datousuario['u_nombre'];
$u_celular = $datousuario['u_celular'];
$u_usuario = $datousuario['u_usuario'];
$u_contrasena = $datousuario['u_contrasena'];
$u_tipo = $datousuario['u_tipo'];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accionlogin = $_POST['accion'];
    if ($accionlogin == "login") {
        include_once 'perfil.php';
    }
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

<div class="titulo">
    <center>
        <a href="dashboard"><img src="imagen/banner.png" width="15%" height="15%"><br></a>
        <strong><?php echo $u_nombre;  ?></strong>
    </center>
</div>


<nav class="navbar navbar-expand-lg ">
    <div class="container-fluid ">
        <span class="navbar-brand"></span>
        <button class="navbar-toggler" style="color:white;" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars"></i></button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ">

                <?php if ($u_tipo == 'ADMINISTRADOR') { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard"><i class="fa-solid fa-house"></i>&nbsp;&nbsp;Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="usuario"><i class="fa-solid fa-users"></i>&nbsp;&nbsp;Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="vehiculo"><i class="fa-solid fa-car-side"></i>&nbsp;&nbsp;Vehiculos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="celda"><i class="fa-solid fa-boxes-stacked"></i>&nbsp;&nbsp;Celda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="parqueo"><i class="fa-solid fa-square-parking"></i>&nbsp;&nbsp;Parqueo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="controlparqueo"><i class="fa-solid fa-list"></i>&nbsp;&nbsp;Control Parqueo</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-dollar"></i>&nbsp;&nbsp;Pagos
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="pagodebe"> <i class="fa-solid fa-dollar"></i>&nbsp;&nbsp;DEBE</a></li>
                            <li><a class="dropdown-item" href="pagocancelado"> <i class="fa-solid fa-dollar"></i>&nbsp;&nbsp;CANCELADO</a></li>
                        </ul>
                    </li>



                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-desktop"></i>&nbsp;&nbsp;Sistema
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="sistema"> <i class="fa-solid fa-circle-info"></i>&nbsp;&nbsp;Configuración</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#perfil"><i class="fa-solid fa-gear"></i>&nbsp;&nbsp;</i>Perfil</a></li>
                            <li><a class="dropdown-item" href="salir" style="color: oramge;"><i class="fa-solid fa-power-off"></i> </i>&nbsp;&nbsp;Salir</a></li>
                        </ul>
                    </li>

                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="miparqueo"><i class="fa-solid fa-square-parking"></i>&nbsp;&nbsp;Mi Parqueo</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-desktop"></i>&nbsp;&nbsp;Sistema
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#perfil"><i class="fa-solid fa-gear"></i>&nbsp;&nbsp;</i>Perfil</a></li>
                            <li><a class="dropdown-item" href="salir" style="color: oramge;"><i class="fa-solid fa-power-off"></i> </i>&nbsp;&nbsp;Salir</a></li>
                        </ul>
                    </li>

                <?php } ?>


            </ul>
        </div>
    </div>
</nav>
<br>
<script type="text/javascript">
    const $dropdown = $(".dropdown");
    const $dropdownToggle = $(".dropdown-toggle");
    const $dropdownMenu = $(".dropdown-menu");
    const showClass = "show";

    $(window).on("load resize", function() {
        if (this.matchMedia("(min-width: 768px)").matches) {
            $dropdown.hover(
                function() {
                    const $this = $(this);
                    $this.addClass(showClass);
                    $this.find($dropdownToggle).attr("aria-expanded", "true");
                    $this.find($dropdownMenu).addClass(showClass);
                },
                function() {
                    const $this = $(this);
                    $this.ruoveClass(showClass);
                    $this.find($dropdownToggle).attr("aria-expanded", "false");
                    $this.find($dropdownMenu).ruoveClass(showClass);
                }
            );
        } else {
            $dropdown.off("mouseenter mouseleave");
        }
    });
</script>

<div class="modal fade" id="perfil" tabindex="-1" aria-labelledby="exampluodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content ">
            <div class="modal-header text-white">
                <h6 class="modal-title" id="perfil">ACTUALIZAR USUARIO</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form ROLE="FORM" METHOD="POST" ACTION="">
                    <input type="hidden" class="form-control" id="accion" name="accion" value="login">
                    <input type="hidden" class="form-control" name="u_tipo" class="form-control" value="<?php echo !empty($u_tipo) ? $u_tipo : ''; ?>" required>

                    <input type="hidden" class="form-control form-control-sm" id="id" name="id" value="<?php echo !empty($u_id) ? $u_id : ''; ?>" required>
                    <span>* Ingrese Información</span><br><br>

                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                        <input type="text" class="form-control" name="u_nombre" placeholder="Nombres Completos" value="<?php echo !empty($u_nombre) ? $u_nombre : ''; ?>">
                    </div>
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text"><i class="fa-solid fa-mobile-screen-button"></i></span>
                        <input type="number" class="form-control" name="u_celular" class="form-control" placeholder="No. Celular" value="<?php echo !empty($u_celular) ? $u_celular : ''; ?>" required>
                    </div>

                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                        <input type="text" class="form-control" name="u_usuario" placeholder="Usuario Sistema" value="<?php echo $u_usuario ? $u_usuario : '' ?>" required>
                        <input type="hidden" class="form-control" name="u_usuarioviejo" value="<?php echo $u_usuario ? $u_usuario : '' ?>" required>

                    </div>
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                        <input type="password" class="form-control" name="u_contrasena" placeholder="Contraseña Sistema" value="<?php echo $u_contrasena ? $u_contrasena : '' ?>" required>
                    </div>
                    <center> <button type="submit" class="btn btn-success btn-sm">Actualizar &nbsp;&nbsp;<i class="fa-solid fa-pen-nib"></i></button></center>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="container">