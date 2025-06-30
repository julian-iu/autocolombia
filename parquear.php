<?php
include_once 'header.php';
$activar = 0;
$placaauto = "";
$contactoauto = "";
$cecularauto = "";
$tipolavadoauto = "";
$valorlavado = "";
$lavadoauto = "";
$fechalavado = "";

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $accion = $_POST['accion'];
    if ($accion == 'agregar') { // activar 1 si se registar y 0 sino se registra
        include_once 'lavagregar.php';
    } else if ($accion == 'agregarvehiculo') {
        include_once 'vehagregar.php';
    }
}
$titulo = "LAVADO";

$texto = "*Términos y Condiciones*: 
* El vehículo se entregará al portador del recibo. 
* No aceptamos ordenes telefónicas ni escritas. 
* Retirado el vehículo, no aceptamos ningún tipo de reclamo. 
* No respondemos por objetos dejados en el vehículo. 
* No respondemos por la pérdida, deterioro o daños ocurridos como consecuencia de incendio, terremoto, asonada, revolución, u otras causas similares. 
* El conductor debe asegurarse que el vehículo está bien asegurado.
* No respondemos por daños al vehículo causados por terceros. 
*Ingreso de Lavada*
*PLACA*: $placaauto
*Contacto*: $contactoauto
*Celular*: $cecularauto
*Tipo Lavado*: $tipolavadoauto
*Valor*: $valorlavado
*Lavador*: $lavadoauto
*Fecha*: $fechalavado
";
?>

<p id="mitexto" style="display:none;"><?php echo $texto; ?></p>

<script>
    var mensaje = <?php echo $activar; ?>;
    let linea = document.getElementById('mitexto').innerHTML;
    var celular = <?php echo $cecularauto; ?>;
    if (mensaje == 1) {
        var copiarContenido = async () => {
            try {
                await navigator.clipboard.writeText(linea);
                alert("Solo pegue el texto al mensaje Whatsaap");
                window.open('https://api.whatsapp.com/send?phone=57' + celular + '', '_blank');
            } catch (err) {
                console.error('Error al copiar: ', err);
            }
        }
        copiarContenido();
    } else {
        console.error('No realizado: ', err);

    }
</script>

<body>
    <?php
    include_once 'menumovil.php';
    ?>

    <!--CONTENIDO-->
    <div class="card">
        <div class="card-header text-white">
        <strong>REGISTRAR <?php echo $titulo; ?> </strong>
        </div>
        <div class="card-body">
            <form ROLE="FORM" METHOD="POST" ACTION="">
                <input type="hidden" class="form-control" id="accion" name="accion" value="agregar">

                <div class="mb-3">
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text"><i class="fa-solid fa-car-side"></i></span>
                        <input type="text" class="form-control" name="v_placa" id="v_placa" value="<?php echo $_SESSION['v_placa'] ? $_SESSION['v_placa'] : '' ?>" class="form-control" placeholder="Ingrese la PLACA del Vehiculo" required>
                    </div>
                    <div id="texto" class="form-text"></div>
                </div>



                <div class="input-group input-group-sm mb-3">
                    <span class="input-group-text"><i class="fa-regular fa-calendar"></i></span>
                    <input type="date" class="form-control" name="l_fecha" class="form-control" value="<?php echo !empty($fechasistema) ? $fechasistema : ''; ?>" placeholder="Fecha Nacimiento" required>
                </div>
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="fa-solid fa-clock"></i></span>
                    <input type="time" class="form-control" name="l_hora" class="form-control" value="<?php echo !empty($horasistema) ? $horasistema : ''; ?>" placeholder="Fecha Nacimiento" required>
                </div>
                <span style="font-size: 12px;">* Si la PLACA del Vehiculo no se encuentra registrada en el sistema, debe ingresarla. De lo contrarrio no aplicará la validación para lavarlo.</span>
                <br><br>
                <center> <button type="btn" class="btn btn-primary btn-sm">Registrar &nbsp;&nbsp;&nbsp;&nbsp;<i class="fa-regular fa-floppy-disk"></i></button></center>
            </form>
        </div>
    </div>
</body>



<script>
    const element = document.getElementById("v_placa");
    element.addEventListener("keydown", function() {

        $.get("buscarvehiculo.php", {
            placa: element.value
        }, function(data) {
            //            document.getElementById("demo").innerHTML
            $("#texto").html(data);
        });

        // document.getElementById("demo").innerHTML = element.value;
    });
</script>


<?php include_once 'footer.php' ?>

</html>