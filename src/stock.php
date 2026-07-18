<?php 
include_once "includes/header.php";
require_once "../conexion.php";

// --- VALIDACIÓN DE SEGURIDAD ---
$id_user = $_SESSION['idUser'];
$sql_permiso = mysqli_query($conexion, "SELECT * FROM detalle_permisos WHERE id_usuario = $id_user AND id_permiso = 7");
if (mysqli_num_rows($sql_permiso) == 0 && $id_user != 1) {
    header("Location: permisos.php");
    exit;
}
// --- FIN VALIDACIÓN ---
?>

<!-- Contenido principal -->
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h2 class="text-center">Stock de Productos Disponible</h2>
        </div>
        <div class="card-body">
            <!-- Buscador -->
            <div class="row justify-content-center mb-4">
                <div class="col-md-6">
                    <input type="text" id="busqueda" class="form-control" placeholder="Buscar por nombre o código...">
                </div>
            </div>
            
            <div class="row" id="contenedor-productos">
                <?php
                // Consulta de productos
                $query = mysqli_query($conexion, "SELECT * FROM producto WHERE estado = 1");
                
                if (mysqli_num_rows($query) > 0) {
                    while ($data = mysqli_fetch_assoc($query)) {
                        // Ruta de las imágenes
                        $ruta_base = "../Img_Productos/"; 
                        $archivo_img = $ruta_base . $data['codproducto'] . ".jpg";
                        
                        // Validación de imagen
                        if (file_exists($archivo_img)) {
                            $img = $archivo_img;
                        } else {
                            $img = $ruta_base . "Sin Img.png";
                        }

                        // Lógica para stock bajo
                        $stock = $data['existencia'];
                        $alerta_stock = ($stock <= 10) ? 'border-danger' : ''; 
                        $texto_stock = ($stock <= 10) ? 'text-danger font-weight-bold' : '';
                ?>
                <!-- Producto -->
                <div class="col-md-2 col-lg-2 mb-4 producto-item" style="flex: 0 0 20%; max-width: 20%;" 
                     data-nombre="<?php echo strtolower($data['descripcion']); ?>" 
                     data-codigo="<?php echo strtolower($data['codigo']); ?>">
                    
                    <div class="card h-100 shadow-sm <?php echo $alerta_stock; ?>">
                        <?php if ($stock <= 10) { ?>
                            <div class="bg-danger text-white text-center small">¡Pocas unidades!</div>
                        <?php } ?>
                        
                        <img src="<?php echo $img; ?>" class="card-img-top" alt="Producto" style="height: 220px; width: 150px; object-fit: cover; display: block; margin: auto;">
                        <div class="card-body p-2 text-center">
                            <h6 class="card-title font-weight-bold" style="font-size: 0.9rem;"><?php echo $data['descripcion']; ?></h6>
                            <p class="card-text mb-1 text-primary">Precio: $<?php echo $data['precio']; ?></p>
                            <p class="card-text small <?php echo $texto_stock; ?>">
                                Disponibles: <strong><?php echo $stock; ?></strong>
                            </p>
                        </div>
                    </div>
                </div>
                <?php
                    }
                } else {
                    echo "<div class='col-12'><p class='text-center'>No hay productos registrados en el stock.</p></div>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php require_once "includes/footer.php"; ?>

<!-- Script de búsqueda en tiempo real -->
<script>
    $(document).ready(function(){
        $("#busqueda").on("keyup", function() {
            var valor = $(this).val().toLowerCase();
            $(".producto-item").filter(function() {
                var texto = $(this).data("nombre") + " " + $(this).data("codigo");
                $(this).toggle(texto.indexOf(valor) > -1);
            });
        });
    });
</script>