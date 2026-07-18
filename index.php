<?php
session_start();
if (!empty($_SESSION['active'])) {
    header('location: src/');
} else {
    if (!empty($_POST)) {
        $alert = '';
        if (empty($_POST['usuario']) || empty($_POST['clave'])) {
            $alert = '<div class="alert alert-danger" role="alert">
            Ingrese su usuario y su clave
            </div>';
        } else {
            require_once "conexion.php";
            $user = mysqli_real_escape_string($conexion, $_POST['usuario']);
            $clave = md5(mysqli_real_escape_string($conexion, $_POST['clave']));
            $query = mysqli_query($conexion, "SELECT * FROM usuario WHERE usuario = '$user' AND clave = '$clave' AND estado = 1");
            mysqli_close($conexion);
            $resultado = mysqli_num_rows($query);
            if ($resultado > 0) {
                $dato = mysqli_fetch_array($query);
                $_SESSION['active'] = true;
                $_SESSION['idUser'] = $dato['idusuario'];
                $_SESSION['nombre'] = $dato['nombre'];
                $_SESSION['user'] = $dato['usuario'];
                header('location: src/');
            } else {
                $alert = '<div class="alert alert-danger" role="alert">
                Usuario o Contraseña Incorrecta
                </div>';
                session_destroy();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Iniciar Sessión</title>
    <link href="assets/css/styles.css" rel="stylesheet" />

    <script src="assets/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body style="background-image: url('assets/img/Dulces_Local.png'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed; min-height: 100vh;">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container-fluid px-5">
                    <div class="row justify-content-end align-items-center" style="min-height: 90vh;">
                        <div class="col-lg-3 col-md-5">
                            <div class="card shadow-lg border-0 rounded-lg" style="background-color: rgba(255, 255, 255, 0.92);">
                                
                                <div class="card-header text-center p-2">

                                    <img class="img-thumbnail" src="assets/img/Solo_Logo_OP.png" width="70">

                                    <h4 class="font-weight-bold my-2" style="font-size: 1.15rem; color: #333;">Distribuidora Mayorista<br>El Sabor de OP<br>2026</h4>
                                </div>

                                <div class="card-body p-3">
                                    <form action="" method="POST">
                                        <div class="form-group mb-2">
                                            <label class="small mb-1" for="usuario"><i class="fas fa-user"></i> Usuario</label>
                                            <input class="form-control py-3" id="usuario" name="usuario" type="text" placeholder="Usuario" required />
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="small mb-1" for="clave"><i class="fas fa-key"></i> Contraseña</label>
                                            <input class="form-control py-3" id="clave" name="clave" type="password" placeholder="Contraseña" required />
                                        </div>
                                        
                                        <?php echo isset($alert) ? $alert : ''; ?>
                                        
                                        <div class="form-group mb-0">
                                            <button class="btn btn-primary btn-block py-2" type="submit">Login</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="assets/js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="assets/js/scripts.js"></script>
</body>

</html>