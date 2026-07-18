<?php 
include_once "includes/header.php";
require("../conexion.php");

$id_user = $_SESSION['idUser'];
$permiso = "nueva_venta";

// Obtener permiso
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);

if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
    exit;
}

// Obtener valor del dólar configurado
$query_config = mysqli_query($conexion, "SELECT dolar FROM configuracion LIMIT 1");
$data_config = mysqli_fetch_assoc($query_config);
$tasa_dolar = $data_config['dolar'] ?? 1.00;
?>

<div class="row">
    <div class="col-lg-12">
        <div class="form-group">
            <h4 class="text-center">Datos del Cliente</h4>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <form method="post">
                    <div class="row">
                        <div class="col-lg-4">
                            <input type="hidden" id="idcliente" value="1" name="idcliente" required>
                            <label>Nombre</label>
                            <input type="text" name="nom_cliente" id="nom_cliente" class="form-control" placeholder="Ingrese nombre del cliente" required>
                        </div>
                        <div class="col-lg-4">
                            <label>Teléfono</label>
                            <input type="number" name="tel_cliente" id="tel_cliente" class="form-control" disabled required>
                        </div>
                        <div class="col-lg-4">
                            <label>Dirección</label>
                            <input type="text" name="dir_cliente" id="dir_cliente" class="form-control" disabled required>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-9">
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white text-center">Datos Venta</div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <label><i class="fas fa-user"></i> VENDEDOR</label>
                                <p class="text-danger font-weight-bold" style="font-size: 16px; text-transform: uppercase;">
                                    <?php echo $_SESSION['nombre']; ?>
                                </p>
                            </div>
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header">Buscar Producto</div>
                                    <div class="card-body">
                                        <input type="hidden" id="id_producto" name="id_producto">
                                        <input id="producto" class="form-control" type="text" name="producto" placeholder="Ingresa código o nombre">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div id="info_producto" style="margin-bottom: 5px; min-height: 80px;"></div>
                <div id="frame_imagen" style="border: 1px solid #dee2e6; width: 100%; height: 270px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa; border-radius: 5px;">
                    <img id="img_producto" src="../Img_Productos/Sin Img.png" style="max-width: 100%; max-height: 100%; object-fit: cover;" alt="Producto">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="tblDetalle">
                <thead class="thead-dark">
                    <tr>
                        <th>Id</th>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Precio Total</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="detalle_venta"></tbody>
                <tfoot>
                    <tr class="font-weight-bold">
                        <td colspan="4" class="text-right">Total a Pagar</td>
                        <td id="total_pagar">0.00</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="col-md-6">
            <a href="#" class="btn btn-primary" id="btn_generar"><i class="fas fa-save"></i> Generar Venta</a>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>

<script>
$(document).ready(function() {
    const tasaDolar = <?php echo $tasa_dolar; ?>;

    $('#producto').autocomplete({
        source: 'buscar_producto.php',
        minLength: 2,
        focus: function(event, ui) {
            actualizarInfo(ui.item);
            return false; 
        },
        select: function(event, ui) {
            // Validar existencia antes de seleccionar
            if (ui.item.existencia <= 0) {
                alert("Este producto no tiene existencia disponible.");
                return false; // Bloquea la selección
            }

            $('#id_producto').val(ui.item.id);
            actualizarInfo(ui.item);
            agregarProductoTabla(ui.item);
            $(this).val('');
            return false;
        }
    });

    function actualizarInfo(item) {
        const rutaImagen = item.foto ? item.foto : '../Img_Productos/Sin Img.png';
        $('#img_producto').attr('src', rutaImagen);
        
        let precioBs = (item.precio * tasaDolar).toFixed(2);
        const info = $('#info_producto');
        
        let htmlContent = "";

        if (item.existencia <= 0) {
            htmlContent = `
                <div class="alert alert-danger p-3" style="font-size: 18px;" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <strong>SIN EXISTENCIA</strong>
                </div>`;
        } else if (item.existencia <= 10) {
            htmlContent = `
                <div class="alert alert-warning p-3" style="font-size: 16px;" role="alert">
                    <i class="fas fa-boxes"></i> <strong>POCOS: ${item.existencia} unidades</strong><br>
                    <span style="font-size: 20px; font-weight: bold;">$${item.precio} | ${precioBs} Bs</span>
                </div>`;
        } else {
            htmlContent = `
                <div class="alert alert-success p-3" style="font-size: 16px;" role="alert">
                    <i class="fas fa-check-circle"></i> <strong>Precio:</strong><br>
                    <span style="font-size: 24px; font-weight: bold;">$${item.precio}</span><br>
                    <span style="font-size: 18px; color: #555;">${precioBs} Bolívares</span>
                </div>`;
        }
        info.html(htmlContent);
    }

    function agregarProductoTabla(item) {
        let existe = false;
        $('#tblDetalle tbody tr').each(function() {
            if ($(this).find('td:eq(0)').text() == item.id) {
                existe = true;
            }
        });

        if (existe) {
            alert("El producto ya está en la lista.");
            return;
        }

        const fila = `
            <tr>
                <td>${item.id}</td>
                <td>${item.label}</td>
                <td><input type="number" value="1" class="form-control" style="width: 80px;" onchange="calcularTotal()"></td>
                <td>${item.precio}</td>
                <td>${item.precio}</td>
                <td><button class="btn btn-danger btn-sm" onclick="$(this).closest('tr').remove(); calcularTotal();">Eliminar</button></td>
            </tr>`;
        
        $('#detalle_venta').append(fila);
        calcularTotal();
    }
});

function calcularTotal() {
    let total = 0;
    $('#tblDetalle tbody tr').each(function() {
        let precio = parseFloat($(this).find('td:eq(3)').text());
        let cantidad = parseInt($(this).find('input').val()) || 0;
        let subtotal = precio * cantidad;
        $(this).find('td:eq(4)').text(subtotal.toFixed(2));
        total += subtotal;
    });
    $('#total_pagar').text(total.toFixed(2));
}
</script>
