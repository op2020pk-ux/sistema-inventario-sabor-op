<?php
include_once "includes/header.php";
include "../conexion.php";

$id_user = $_SESSION['idUser'];
$permiso = "productos";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}

if (!empty($_POST)) {
    $alert = "";
    if (empty($_POST['codigo']) || empty($_POST['producto']) || empty($_POST['precio'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todos los campos son requeridos</div>';
    } else {
        $codproducto = $_GET['id'];
        $codigo = $_POST['codigo'];
        $producto = $_POST['producto'];
        $precio = $_POST['precio'];
        
        // Actualizar datos básicos
        $query_update = mysqli_query($conexion, "UPDATE producto SET codigo = '$codigo', descripcion = '$producto', precio = $precio WHERE codproducto = $codproducto");
        
        // Procesar la nueva imagen si se seleccionó
        if (!empty($_FILES['foto']['name'])) {
            $foto = $_FILES['foto'];
            $nombre_archivo = $codproducto . ".jpg"; // Mantiene el nombre basado en el ID
            $destino = "../Img_Productos/" . $nombre_archivo;
            move_uploaded_file($foto['tmp_name'], $destino);
        }

        if ($query_update) {
            $alert = '<div class="alert alert-success" role="alert">Producto Modificado</div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">Error al Modificar</div>';
        }
    }
}

// Validar producto
if (empty($_REQUEST['id'])) {
    header("Location: productos.php");
} else {
    $id_producto = $_REQUEST['id'];
    $query_producto = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $id_producto");
    if (mysqli_num_rows($query_producto) > 0) {
        $data_producto = mysqli_fetch_assoc($query_producto);
    } else {
        header("Location: productos.php");
    }
}
?>

<div class="row">
    <div class="col-lg-6 m-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">Modificar producto</div>
            <div class="card-body">
                <!-- Se agregó enctype para permitir subir archivos -->
                <form action="" method="post" enctype="multipart/form-data">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <div class="form-group">
                        <label for="codigo">Código de Barras</label>
                        <input type="text" name="codigo" id="codigo" class="form-control" value="<?php echo $data_producto['codigo']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="producto">Producto</label>
                        <input type="text" name="producto" id="producto" class="form-control" value="<?php echo $data_producto['descripcion']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio</label>
                        <input type="text" name="precio" id="precio" class="form-control" value="<?php echo $data_producto['precio']; ?>">
                    </div>
                    <!-- Campo para modificar la foto -->
                    <div class="form-group">
                        <label for="foto">Cambiar Imagen (opcional)</label>
                        <input type="file" name="foto" id="foto" class="form-control">
                    </div>
                    <input type="submit" value="Actualizar Producto" class="btn btn-primary">
                    <a href="productos.php" class="btn btn-danger">Atras</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>