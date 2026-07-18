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
    $codigo = $_POST['codigo'];
    $producto = $_POST['producto'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $usuario_id = $_SESSION['idUser'];
    $alert = "";

    if (empty($codigo) || empty($producto) || empty($precio) || $precio < 0 || empty($cantidad) || $cantidad < 0) {
        $alert = '<div class="alert alert-danger" role="alert">Todos los campos son obligatorios</div>';
    } else {
        $query = mysqli_query($conexion, "SELECT * FROM producto WHERE codigo = '$codigo'");
        $result = mysqli_fetch_array($query);
        if ($result > 0) {
            $alert = '<div class="alert alert-warning" role="alert">El código ya existe</div>';
        } else {
            // Inserción inicial
            $query_insert = mysqli_query($conexion, "INSERT INTO producto(codigo,descripcion,precio,existencia,usuario_id) VALUES ('$codigo', '$producto','$precio','$cantidad','$usuario_id')");
            
            if ($query_insert) {
                // Gestión de la imagen
                $id_producto = mysqli_insert_id($conexion);
                if (!empty($_FILES['foto']['name'])) {
                    $foto = $_FILES['foto'];
                    $nombre_archivo = $id_producto . ".jpg";
                    $destino = "../Img_Productos/" . $nombre_archivo;
                    move_uploaded_file($foto['tmp_name'], $destino);
                }
                $alert = '<div class="alert alert-success" role="alert">Producto Registrado correctamente</div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">Error al registrar el producto</div>';
            }
        }
    }
}
?>

<button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nuevo_producto"><i class="fas fa-plus"></i></button>
<?php echo isset($alert) ? $alert : ''; ?>

<div class="table-responsive">
    <table class="table table-striped table-bordered" id="tbl">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Código</th>
                <th>Producto</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = mysqli_query($conexion, "SELECT * FROM producto");
            if (mysqli_num_rows($query) > 0) {
                while ($data = mysqli_fetch_assoc($query)) {
                    $estado = ($data['estado'] == 1) ? '<span class="badge badge-pill badge-success">Activo</span>' : '<span class="badge badge-pill badge-danger">Inactivo</span>';
            ?>
                <tr>
                    <td><?php echo $data['codproducto']; ?></td>
                    <td><?php echo $data['codigo']; ?></td>
                    <td><?php echo $data['descripcion']; ?></td>
                    <td><?php echo $data['precio']; ?></td>
                    <td><?php echo $data['existencia']; ?></td>
                    <td><?php echo $estado ?></td>
                    <td>
                        <?php if ($data['estado'] == 1) { ?>
                            <a href="agregar_producto.php?id=<?php echo $data['codproducto']; ?>" class="btn btn-primary"><i class='fas fa-audio-description'></i></a>
                            <a href="editar_producto.php?id=<?php echo $data['codproducto']; ?>" class="btn btn-success"><i class='fas fa-edit'></i></a>
                            <form action="eliminar_producto.php?id=<?php echo $data['codproducto']; ?>" method="post" class="confirmar d-inline">
                                <button class="btn btn-danger" type="submit"><i class='fas fa-trash-alt'></i> </button>
                            </form>
                        <?php } ?>
                    </td>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>

<!-- Modal Nuevo Producto -->
<div id="nuevo_producto" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Nuevo Producto</h5>
                <button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <!-- Se agregó enctype para permitir subir archivos -->
                <form action="" method="post" autocomplete="off" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="codigo">Código de Barras</label>
                        <input type="text" placeholder="Ingrese código de barras" name="codigo" id="codigo" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="producto">Producto</label>
                        <input type="text" placeholder="Ingrese nombre del producto" name="producto" id="producto" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio</label>
                        <input type="text" placeholder="Ingrese precio" class="form-control" name="precio" id="precio">
                    </div>
                    <div class="form-group">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" placeholder="Ingrese cantidad" class="form-control" name="cantidad" id="cantidad">
                    </div>
                    <!-- Campo para la foto -->
                    <div class="form-group">
                        <label for="foto">Imagen del Producto</label>
                        <input type="file" name="foto" id="foto" class="form-control">
                    </div>
                    <input type="submit" value="Guardar Producto" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>