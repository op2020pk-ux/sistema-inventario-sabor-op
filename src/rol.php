<?php
include_once "includes/header.php";
require_once "../conexion.php";

$id = $_GET['id'];

// Consultas iniciales
$sqlpermisos = mysqli_query($conexion, "SELECT * FROM permisos");
$usuarios = mysqli_query($conexion, "SELECT * FROM usuario WHERE idusuario = $id");
$consulta = mysqli_query($conexion, "SELECT * FROM detalle_permisos WHERE id_usuario = $id");
$resultUsuario = mysqli_num_rows($usuarios);

// Verificación de existencia del usuario
if (empty($resultUsuario)) {
    header("Location: usuarios.php");
    exit;
}

// Cargar permisos actuales del usuario
$datos = array();
foreach ($consulta as $asignado) {
    $datos[$asignado['id_permiso']] = true;
}

// Lógica de procesamiento (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_user = $_GET['id'];
    
    // Eliminamos siempre los permisos previos del usuario antes de actualizar
    mysqli_query($conexion, "DELETE FROM detalle_permisos WHERE id_usuario = $id_user");

    // Si existen permisos seleccionados (checkboxes marcados), los insertamos
    if (isset($_POST['permisos'])) {
        $permisos = $_POST['permisos'];
        foreach ($permisos as $permiso) {
            mysqli_query($conexion, "INSERT INTO detalle_permisos(id_usuario, id_permiso) VALUES ($id_user, $permiso)");
        }
    }
    
    header("Location: rol.php?id=" . $id_user . "&m=si");
    exit;
}
?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">Permisos de Usuario</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="rol.php?id=<?php echo $id; ?>">
                    <?php if (isset($_GET['m']) && $_GET['m'] == 'si') { ?>
                        <div class="alert alert-success" role="alert">
                            Permisos actualizados correctamente
                        </div>
                    <?php } ?>

                    <div class="row">
                        <?php while ($row = mysqli_fetch_assoc($sqlpermisos)) { ?>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center justify-content-between p-2 border rounded">
                                    <label class="mb-0 text-uppercase font-weight-bold" style="font-size: 14px;">
                                        <?php echo $row['nombre']; ?>
                                    </label>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="permiso_<?php echo $row['id']; ?>" name="permisos[]" value="<?php echo $row['id']; ?>" <?php if (isset($datos[$row['id']])) { echo "checked"; } ?>>
                                        <label class="custom-control-label" for="permiso_<?php echo $row['id']; ?>"></label>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    
                    <button class="btn btn-primary btn-block mt-3" type="submit">Modificar Permisos</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>