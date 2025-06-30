<?php
include_once 'configuracion/conexion.php';
$pdo = Conexion::conectar();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sqlconsulta = $_GET['sql'];
$sumapagado = $_GET['suma'];
$estado = $_GET['estado'];



$buscarinfo = 'SELECT * FROM empresa WHERE em_id = ?';
$perq = $pdo->prepare($buscarinfo);
$perq->execute(array(1));
$datoempresa = $perq->fetch(PDO::FETCH_ASSOC);
$em_nit = $datoempresa['em_nit'];
$em_razonsocial = $datoempresa['em_razonsocial'];
$em_propietario = $datoempresa['em_propietario'];
$em_celular = $datoempresa['em_celular'];
$em_usuario = $datoempresa['em_usuario'];
$em_contrasena = $datoempresa['em_contrasena'];

date_default_timezone_set('America/Bogota');
$hora = date("H:i:s");
$fecha = date("Y-m-d");

$sumatoria = 0;

ob_start();

?>
<style>
    table {
        width: 100%;
        font-family: 'Arial';
    }

    th,
    td {
        border: 0.5px solid;
        font-family: 'Arial';
    }
</style>

<table>
    <thead style="text-align: center; background-color: #0080cb; color: white;  font-family: 'Arial';">
        <tr style="text-align: center; ">
            <td style="width: 66.6666%; height: 78px;" colspan="8">
                <p style="text-align: center; font-weight: bold"><?php echo $em_razonsocial ?></p>
                Nit No.: <?php echo $em_nit; ?><br />
                Propietario: <?php echo $em_propietario; ?><br />
                Whatsaap: <?php echo $em_celular; ?><br />
                </p>
            </td>
        </tr>
    </thead>
    <thead style="text-align: center; background-color: #04c5fe; color: white;">
        <tr style="text-align: center; ">
            <?php echo $estado;
            if ($estado == 1) { ?>
                <td colspan="8">
                    <strong>FACTURA DE PAGOS DE NOMINA</strong>
                </td>
            <?php } else {
                $ejecutar = $pdo->prepare($sqlconsulta);
                $ejecutar->execute();
                $info = $ejecutar->fetch(PDO::FETCH_ASSOC); ?>
                <td colspan="8">
                    <strong>FACTURA DE PAGOS DE EMPLEADO</strong><br />
                    <strong style="color:black;"> EMPLEADO: <?php echo $info['h_identificacionlav'] . " -- " . $info['h_lavador']; ?> </strong>
                </td>
            <?php } ?>

        </tr>
    </thead>

    <thead style="text-align: center; ">
        <tr>
            <?php if ($estado == 1) { ?>
                <th style="width: 20%;">Identificacion</th>
                <th style="width: 20%;">lavador</th>
            <?php } else {
            } ?>
            <th style="width: 20%;">PLACA</th>
            <th style="width: 10%;">Codigo Lavado</th>
            <th style="width: 20%;">Tipo vehiculo</th>
            <th style="width: 10%;">Precio</th>
            <th style="width: 5%;">%</th>
            <th style="width: 10%;">Cancelado<th>
        </tr>
    </thead>

    <tbody>

        <?php
        foreach ($pdo->query($sqlconsulta) as $dato) {
        ?>
            <tr>
                <?php if ($estado == 1) { ?>
                    <td style="width: 20%;"><?php echo $dato['h_identificacionlav'] ?></td>
                    <td style="width: 20%;"><?php echo $dato['h_lavador'] ?></td>
                    <td style="width: 20%;"><?php echo $dato['h_placa'] ?></td>
                    <td style="width: 10%;"><?php echo $dato['h_codigo'] ?></td>
                    <td style="width: 20%;"><?php echo $dato['h_vehiculo'] ?></td>
                    <td style="text-align: right;width: 20%;"><?php echo number_format($dato["h_precio"], 0, ",", "."); ?></td>
                    <th style="text-align: right;width: 5%;"><?php echo $dato['h_porcentaje'] ?></th>
                    <th style="text-align: right;width: 10%;"><?php echo number_format($dato["h_pagado"], 0, ",", "."); ?><th>

                <?php } else { ?>
                    <td style="width: 20%;"><?php echo $dato['h_placa'] ?></td>
                    <td style="width: 10%;"><?php echo $dato['h_codigo'] ?></td>
                    <td style="width: 20%;"><?php echo $dato['h_vehiculo'] ?></td>
                    <td style="text-align: right;width: 30%;"><?php echo number_format($dato["h_precio"], 0, ",", "."); ?></td>
                    <th style="text-align: right;width: 10%;"><?php echo $dato['h_porcentaje'] ?></th>
                    <th style="text-align: right;width: 10%;"><?php echo number_format($dato["h_pagado"], 0, ",", "."); ?><th>

                <?php } ?>

            </tr>
        <?php
            $sumatoria = $sumatoria + $dato["h_precio"];
        }

        ?>
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th style="width: 80%;">Descripcion</th>
            <th style="width: 20%;">Valores</th>
        </tr>
    </thead>
    <tbody>
        <?php
        echo $estado;
        if ($estado != 0) { ?>
            <tr style="text-align: right;">
                <td style="width: 80%;">Precio Lavadero</td>
                <td style="width: 20%;"><strong>$ <?php echo number_format($sumatoria, 0, ",", "."); ?></strong></td>
            </tr>
            <tr style="text-align: right;">
                <td style="width: 80%;">%  Cancelado</td>
                <td style="width: 20%;"><strong>$ <?php echo number_format($sumapagado, 0, ",", "."); ?></strong></td>
            </tr>
        <?php } ?>

    </tbody>
</table>


<p>Impresion de reporte: <?php echo $fecha . " - " . $hora; ?></p>
<p style="font-size: 12px;color:#04c5fe; "><?php echo $em_razonsocial; ?></p>
<?php
$html = ob_get_clean();

require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);
$dompdf->loadHtml($html);

if ($estado == 1) {
    $dompdf->setPaper('letter', 'landscape');
} else {
    $dompdf->setPaper('letter', '');
}
$dompdf->render();
$dompdf->stream("reporte.pdf", array("Attachment" => true));
// Guardamos a PDF
?>;