<?php
require("../conexion.php");

if (isset($_GET['term'])) {
    $busqueda = mysqli_real_escape_string($conexion, $_GET['term']);
    
    // Consulta los datos del producto
    $query = mysqli_query($conexion, "SELECT codproducto, descripcion, precio, existencia FROM producto WHERE codigo = '$busqueda' OR descripcion LIKE '%$busqueda%' LIMIT 10");
    
    $data = array();
    
    while ($row = mysqli_fetch_assoc($query)) {
        $id_prod = $row['codproducto'];
        $ruta_img = "../Img_Productos/" . $id_prod . ".jpg";
        
        // Si no existe el archivo .jpg, envía la imagen por defecto
        if (!file_exists($ruta_img)) {
            $ruta_img = "../Img_Productos/Sin Img.png";
        }
        
        $data[] = [
            'id'         => $id_prod,
            'label'      => $row['descripcion'],
            'value'      => $row['descripcion'],
            'foto'       => $ruta_img, // Esta ruta llega al script de ventas
            'precio'     => $row['precio'],
            'existencia' => $row['existencia']
        ];
    }
    echo json_encode($data);
}
?>