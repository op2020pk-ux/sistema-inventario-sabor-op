<?php 
include_once "includes/header.php";
require_once "../conexion.php";

$id_user = $_SESSION['idUser'];
$permiso = "configuracion";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}

$query = mysqli_query($conexion, "SELECT * FROM configuracion");
$data = mysqli_fetch_assoc($query);

if ($_POST) {
    $alert = '';
    if (empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['email']) || empty($_POST['direccion']) || empty($_POST['dolar'])) {
        $alert = '<div class="alert alert-danger" role="alert">Todo los campos son obligatorios</div>';
    } else {
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
        $direccion = $_POST['direccion'];
        $dolar = $_POST['dolar'];
        $id = $_POST['id'];
        
        $update = mysqli_query($conexion, "UPDATE configuracion SET nombre = '$nombre', telefono = '$telefono', email = '$email', direccion = '$direccion', dolar = '$dolar' WHERE id = $id");
        
        if ($update) {
            $alert = '<div class="alert alert-success" role="alert">Datos modificados correctamente</div>';
            // Recargamos los datos para mostrar el valor actualizado en el formulario
            $data['nombre'] = $nombre;
            $data['telefono'] = $telefono;
            $data['email'] = $email;
            $data['direccion'] = $direccion;
            $data['dolar'] = $dolar;
        }
    }
}
?>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Datos de la Empresa y Tasa del Dólar
            </div>
            <div class="card-body">
                <form action="" method="post" class="p-3">
                    <input type="hidden" name="id" value="<?php echo $data['id'] ?>">
                    
                    <div class="form-group">
                        <label>Nombre:</label>
                        <input type="text" name="nombre" class="form-control" value="<?php echo $data['nombre']; ?>" id="txtNombre" placeholder="Nombre de la Empresa" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Teléfono:</label>
                        <input type="number" name="telefono" class="form-control" value="<?php echo $data['telefono']; ?>" id="txtTelEmpresa" placeholder="Teléfono de la Empresa" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Correo Electrónico:</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $data['email']; ?>" id="txtEmailEmpresa" placeholder="Correo de la Empresa" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Dirección:</label>
                        <input type="text" name="direccion" class="form-control" value="<?php echo $data['direccion']; ?>" id="txtDirEmpresa" placeholder="Dirección de la Empresa" required>
                    </div>

                    <div class="form-group">
                        <label>Precio del Dólar (Bs):</label>
                        <input type="number" step="0.01" name="dolar" class="form-control" value="<?php echo $data['dolar']; ?>" id="txtDolar" placeholder="0.00" required>
                    </div>

                    <?php echo isset($alert) ? $alert : ''; ?>
                    
                    <div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Modificar Datos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>