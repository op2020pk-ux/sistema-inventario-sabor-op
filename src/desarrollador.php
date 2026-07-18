<?php 
include_once "includes/header.php";
require "../conexion.php";

// Bloque de seguridad para restringir el acceso
$id_user = $_SESSION['idUser'];
$sql_permiso = mysqli_query($conexion, "SELECT * FROM detalle_permisos WHERE id_usuario = $id_user AND id_permiso = 8");
if (mysqli_num_rows($sql_permiso) == 0 && $id_user != 1) {
    header("Location: permisos.php");
    exit;
}

// Contador dinámico optimizado y corregido para evitar el error de columna
$resProductos = mysqli_query($conexion, "SELECT COUNT(*) as total FROM producto");
$rowProducto = mysqli_fetch_assoc($resProductos);
$totalP = $rowProducto['total'];
?>


<!-- ESTILOS ADICIONALES PARA LA ELEGANCIA -->
<style>
    :root {
        --primary-dark: #2c3e50;
        --secondary-fade: #f4f7f6;
        --accent-gold: #bda871; /* Un toque dorado elegante */
    }

    .ficha-container {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    .card-elegant {
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .callout-elegant {
        background: linear-gradient(135deg, #e9f0f7 0%, #fdfdfd 100%);
        border-left: 5px solid #4e73df;
        border-radius: 12px;
        position: relative;
        overflow: hidden;
    }

    .tech-tile {
        transition: all 0.3s ease;
        border: 1px solid #eee !important;
        border-radius: 14px;
        background: #fff;
    }

    .tech-tile:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(78, 115, 223, 0.1);
        border-color: #4e73df !important;
    }

    .tech-tile i {
        transition: transform 0.3s ease;
    }

    .tech-tile:hover i {
        transform: scale(1.1);
    }

    .icon-shape-elegant {
        width: 48px;
        height: 48px;
        min-width: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: inset 0 0 0 1px rgba(0,0,0,0.03);
    }

    .mention-card {
        background: linear-gradient(70deg, #fffcf4 0%, #fffbf0 100%);
        border-left: 4px solid var(--accent-gold) !important;
    }

    .footer-elegant {
        background-color: var(--primary-dark);
        border-radius: 16px;
        color: #ecf0f1;
    }

    .badge-gold {
        background-color: rgba(189, 168, 113, 0.15);
        color: var(--accent-gold);
        font-weight: 600;
    }
</style>

<div class="container-fluid ficha-container px-4">
    <!-- CABECERA DE PERFIL MODERNA -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
        <h1 class="h3 mb-0 text-gray-900 font-weight-bold">
            <i class="fas fa-id-card text-primary mr-3"></i>Ficha Profesional
        </h1>
        
    </div>

    <div class="row">
        <!-- COLUMNA IZQUIERDA: BIOGRAFÍA Y TECNOLOGÍAS -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card card-elegant h-100">
                <!-- Encabezado sutil -->
                <div class="card-header bg-white py-4 d-flex align-items-center justify-content-between border-0">
                    <h5 class="m-0 font-weight-bold text-gray-900">
                        Perfil del Desarrollador
                    </h5>
                    <span class="badge badge-pill badge-primary-soft px-3 py-2 text-uppercase tracking-wider small" style="background-color: #df974e1a; color: #4e73df;">
                        Principal Developer
                    </span>
                </div>

                <!-- Cuerpo Principal -->
                <div class="card-body pt-0">
                    <!-- Callout de Presentación -->
                    <div class="callout-elegant p-4 mb-4">
                        <div class="row align-items-center">

                            <div class="col-auto">
                                <img src="../img/hacker.png" alt="Omar Pinto" class="rounded-circle bg-white p-1 shadow-sm" style="width: 70px; height: 70px; object-fit: contain;">
                            </div>
                            
                            <div class="col">
                                <h3 class="font-weight-bold text-gray-900 mb-1">Omar Pinto</h3>
                                <p class="text-primary font-weight-medium mb-0" style="font-size: 1.05rem;">
                                    Especialista en Soluciones Web, Escritorio y Bases de Datos
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Textos Biográficos -->
                    <div class="text-gray-800 text-justify mb-4" style="font-size: 0.98rem; line-height: 1.7;">
                        <p>
                            Estudiante avanzado del PNF en Informática en la <strong>Aldea Universitaria José Félix Ribas</strong> (La Victoria, Aragua). Con enfoque en la arquitectura de software modular y la automatización de procesos críticos en entornos comunitarios y educativos.
                        </p>
                        <p class="mb-0">
                            Especializado en el ciclo completo de desarrollo, desde el diseño de bases de datos relacionales robustas hasta la implementación de interfaces gráficas intuitivas, utilizando metodologías ágiles para la optimización de control interno.
                        </p>
                    </div>

                    <hr class="my-4" style="border-top: 1px solid #eee;">

                    <!-- Sección de Tecnologías -->
                    <h6 class="text-uppercase text-muted font-weight-bold tracking-wider small mb-3">
                        <i class="fas fa-tools mr-2"></i>Stack Tecnológico Principal
                    </h6>

                    <div class="row text-center tech-stack-grid">
                        <?php 
                        $techs = [
                            ['fab fa-php text-primary', 'PHP 8 / MySQL'],
                            ['fab fa-python text-warning', 'Python / Tkinter'],
                            ['fas fa-microchip text-secondary', 'Lenguaje C'],
                            ['fab fa-html5 text-danger', 'HTML5 / CSS3 / JS'],
                            ['fas fa-server text-info', 'XAMPP Local'],
                            ['fas fa-database text-success', 'MySQL / SQLite3']
                        ];
                        foreach ($techs as $tech): ?>
                        <div class="col-6 col-sm-4 col-md-4 col-lg-2 mb-3">
                            <div class="p-3 tech-tile h-100 d-flex flex-column align-items-center justify-content-center">
                                <i class="<?php echo $tech[0]; ?> fa-2x mb-3"></i>
                                <div class="small font-weight-bold text-gray-900" style="font-size: 0.78rem; line-height: 1.2;">
                                    <?php echo $tech[1]; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: SOPORTE Y RECONOCIMIENTO -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <!-- Tarjeta de Contacto -->
            <div class="card card-elegant mb-4">
                <div class="card-body p-4">
                    <h6 class="font-weight-bold text-gray-900 mb-4">
                        <i class="fas fa-headset text-primary mr-2"></i>Canales de Soporte
                    </h6>
                    
                    <div class="space-y-3">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-shape-elegant text-primary bg-light mr-3">
                                <i class="fas fa-user-tie fa-lg"></i>
                            </div>
                            <div>
                                <span class="d-block small text-muted">Desarrollador</span>
                                <strong class="text-gray-900">Omar Pinto</strong>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-shape-elegant text-danger bg-light-soft mr-3" style="background-color: #fff1f0;">
                                <i class="fas fa-envelope fa-lg"></i>
                            </div>
                            <div>
                                <span class="d-block small text-muted">Correo Principal</span>
                                <strong class="text-gray-900" style="font-size: 0.9rem;">sistema.edu.op@gmail.com</strong>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-shape-elegant text-success bg-light mr-3">
                                <i class="fas fa-map-marker-alt fa-lg"></i>
                            </div>
                            <div>
                                <span class="d-block small text-muted">Ubicación</span>
                                <strong class="text-gray-900">La Victoria, Aragua</strong>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="icon-shape-elegant text-info bg-light mr-3">
                                <i class="fas fa-briefcase fa-lg"></i>
                            </div>
                            <div>
                                <span class="d-block small text-muted">Ecosistema</span>
                                <strong class="text-gray-900">Soluciones Edu Op</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Mención Honorífica Premium -->
            <div class="card card-elegant mention-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-award fa-lg text-warning mr-2"></i>
                        <h6 class="m-0 font-weight-bold text-gray-900 text-uppercase tracking-wider small">Reconocimiento</h6>
                    </div>
                    <div class="position-relative pl-3" style="border-left: 2px solid #ffe8a1;">
                        <p class="text-gray-800 font-italic small mb-0" style="line-height: 1.7; font-size: 0.9rem;">
                            "Agradecimiento especial al <strong>Profesor Jorge Acosta</strong>, por su labor pedagógica y mentoría en el análisis lógico de sistemas, fundamentales para este desarrollo."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER INFORMATIVO DEL SISTEMA -->
    <div class="row mt-2 mb-4">
        <div class="col-lg-12">
            <div class="card footer-elegant shadow-sm border-0">
                <div class="card-body py-3 px-4">
                    <div class="row align-items-center">
                        <div class="col-md-7 text-center text-md-left mb-2 mb-md-0">
                            <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                                <i class="fas fa-layer-group text-gold mr-3 fa-lg" style="color: #bda871;"></i>
                                <div>
                                    <h6 class="mb-0 font-weight-bold" style="font-size: 1rem; color: #fff;">Entorno Local: XAMPP</h6>
                                    <span class="small" style="color: #bdc3c7;">Plataforma Centralizada <strong>ESCUELA OP</strong></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 text-center text-md-right">
                            <span class="badge badge-gold badge-pill py-2 px-3 shadow-sm">
                                <i class="fas fa-box text-gold mr-2"></i> 
                                <span class="font-weight-bold" style="font-size: 0.9rem;"><?php echo $totalP; ?> Ítems</span> en Inventario
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>