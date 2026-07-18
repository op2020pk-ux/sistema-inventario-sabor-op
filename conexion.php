<?php
    // $host = "192.168.0.200";
    $host = "localhost"; // <-- Para la principal dejamos localhost
    $user = "root";
    $clave = "";
    $bd = "el_sabor_de_op";
    $conexion = mysqli_connect($host, $user, $clave, $bd);
    
    if (mysqli_connect_errno()){
        echo "No se pudo conectar a la base de datos";
        exit();
    }
    mysqli_select_db($conexion,$bd) or die("No se encuentra la base de datos");
    mysqli_set_charset($conexion,"utf8");
?>
