<?php
include_once 'header.php';
$titulopagina = "REPORTES DE LA EMPRESA";
$sqlconsulta = "";
$sqlsuma = "";
$estado = 0;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accionreporte'];
    if ($accion == 'reporte') {
        $fecha = $_POST['fecha'];
        $mes = $_POST['mes'];
        $ano = $_POST['ano'];
        if ($fecha != "" && $mes == "MES" && $ano == "") { // buscar solo fecha
            $sqlconsulta = "SELECT * FROM historial WHERE h_fechasalida='" . $fecha . "'";
            $sqlsuma = "SELECT SUM(h_precio) AS lavado, SUM(h_valorparqueo) parqueo, SUM(h_total) AS total FROM historial WHERE h_fechasalida='" . $fecha . "'";
            $estado = 1;
        } elseif ($fecha == "" && $mes != "MES" && $ano != "") { // buscar ano y mes
            $estado = 1;
            $sqlconsulta = "SELECT * FROM historial WHERE MONTH(h_fechasalida) = " . $mes . " AND YEAR(h_fechasalida) = " . $ano . "";
            $sqlsuma = "SELECT SUM(h_precio) AS lavado, SUM(h_valorparqueo) parqueo, SUM(h_total) AS total FROM historial WHERE MONTH(h_fechasalida) = " . $mes . " AND YEAR(h_fechasalida) = " . $ano . "";
        } 
    } 
}

if ($estado == 0) {
} else {
    $consultasumas = $pdo->prepare($sqlsuma);
    $consultasumas->execute();
    $datosuma = $consultasumas->fetch(PDO::FETCH_ASSOC);
    $sumalavado = 0;
    $sumaparqueo = 0;
    $sumatotal = 0;

    if ($estado == 1) {
        $sumalavado = $datosuma['lavado'];
        $sumaparqueo = $datosuma['parqueo'];
        $sumatotal =  $datosuma['total'];
    } elseif ($estado == 2) {
        $sumalavado = $datosuma['lavado'];
    }
}
?>

<body>
    <div class="container">

        <span><strong>Realizar consulta por:</strong></span><br><br>
        <form ROLE="FORM" METHOD="POST" ACTION="">
            <div class="row">
                <div class="col-3">
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                        <input type="date" class="form-control" name="fecha" class="form-control">
                    </div>
                </div>

                <div class="col-3">
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
                        <select class="form-select form-select-sm" name="mes" id="mes" required>
                            <?php
                            $mes = array("MES", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
                            foreach ($mes as $i => $valor) {
                            ?>
                                <option value="<?php echo $mes[$i] ?>">
                                    <?php echo $mes[$i] ?>
                                </option>
                            <?php

                            }
                            ?>
                        </select>
                    </div>

                </div>
                <div class="col-4">
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text"><i class="fa-solid fa-calendar"></i></span>
                        <input type="number" class="form-control" name="ano" placeholder="Año a Consultar">
                    </div>

                </div>
              
                <div class="col-1">
                    <center> <button type="submit" value="reporte" name="accionreporte" class="btn btn-primary btn-sm"><i class="fa-solid fa-magnifying-glass fa-2x"></i></button></center>
                </div>
                <?php if ($estado == 0) { ?>
                    <div class="col-1">
                    </div>
                <?php } else { ?>
                    <div class="col-1">
                        <center> <a href="reportesxls.php?sql=<?php echo $sqlconsulta; ?>&estado=<?php echo $estado; ?>" class="btn btn-success btn-sm" target="_blank"><i class="fa-solid fa-file-excel fa-2x"></i></a></center>
                    </div>
                <?php } ?>

        </form>
    </div>
    <div class="card">
        <div class="card-header text-white">
            <strong>TABLA DE <?php echo $titulopagina; ?>
            </strong>
        </div>
        <div class="card-body">

            <table id="datatablesSimple" class="display table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>PLACA</th>
                        <th>Tipo Lavado</th>
                        <th>Tipo vehiculo</th>
                        <th>Precio</th>
                        <th>lavador</th>

                        <?php if ($estado == 1) { ?>
                            <th>H. Parqueo</th>
                            <th>Horas</th>
                            <th>Total</th>
                        <?php } else {
                        } ?>

                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>PLACA</th>
                        <th>Tipo Lavado</th>
                        <th>Tipo vehiculo</th>
                        <th>Precio</th>
                        <th>Lavador</th>

                        <?php if ($estado == 1) { ?>
                            <th>H. Parqueo</th>
                            <th>Horas</th>
                            <th>Total</th>
                        <?php } else {
                        } ?>
                    </tr>
                </tfoot>
                <tbody>

                    <?php
                    foreach ($pdo->query($sqlconsulta) as $dato) {
                    ?>
                        <tr>
                            <td><?php echo $dato['h_placa'] ?></td>
                            <td><?php echo $dato['h_codigo'] ?></td>
                            <td><?php echo $dato['h_vehiculo'] ?></td>
                            <td style="text-align: right;"><?php echo number_format($dato["h_precio"], 0, ",", "."); ?></td>
                            <td><?php echo $dato['h_lavador'] ?></td>
                            <?php if ($estado == 1) { ?>
                                <td style="text-align: right;"><?php echo number_format($dato["h_cantidadhora"], 0, ",", "."); ?></td>
                                <td style="text-align: right;"><?php echo number_format(round($dato["h_valorparqueo"], 0), 0, ",", "."); ?></td>
                                <td style="text-align: right;"><?php echo number_format(round($dato["h_total"], 0), 0, ",", "."); ?></td>
                            <?php } else {
                            } ?>

                        </tr>
                    <?php
                    }
                    ?>

                </tbody>

            </table>

            <table class="display table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Descripcion</th>
                        <th>Valores</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($estado == 1) { ?>

                        <tr style="text-align: right;">
                            <td>Precio Lavadero</td>
                            <td>$ <?php echo number_format($sumalavado, 0, ",", "."); ?></td>
                        </tr>
                        <tr style="text-align: right;">
                            <td>Precio Parqueo</td>
                            <td>$ <?php echo number_format($sumaparqueo, 0, ",", "."); ?></td>
                        </tr>
                        <tr style="text-align: right;">
                            <td>Precio Total</td>
                            <td><strong>$ <?php echo number_format($sumatotal, 0, ",", "."); ?></strong></td>
                        </tr>
                    <?php } else { ?>
                        <tr style="text-align: right;">
                            <td>Precio Lavadero</td>
                            <td>$ <?php echo number_format($sumalavado, 0, ",", "."); ?></td>
                        </tr>

                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>

</body>
<?php include_once 'footer.php' ?>
<script src="js/simple-datatables.js" crossorigin="anonymous"></script>

</html>