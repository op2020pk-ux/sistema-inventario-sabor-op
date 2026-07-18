<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';

// Validar que los parámetros existen
if (!isset($_GET['v']) || !isset($_GET['cl'])) {
    die("Error: Parámetros de venta no recibidos.");
}

$id = $_GET['v'];
$idcliente = $_GET['cl'];

// Cambia esto en la parte superior:
//$id = isset($_GET['v']) ? (int)$_GET['v'] : 0;
//$idcliente = isset($_GET['cl']) ? (int)$_GET['cl'] : 0;

if ($id == 0 || $idcliente == 0) {
    die("Error: Parámetros inválidos.");
}

// ----------- //

$pdf = new FPDF('P', 'mm', 'letter');
$pdf->AddPage();
$pdf->SetMargins(10, 10, 10);
$pdf->SetTitle("Ventas");
$pdf->SetFont('Arial', 'B', 12);

// Consultas a la base de datos
$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);

$clientes = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $idcliente");
$datosC = mysqli_fetch_assoc($clientes);


// Consulta mejorada: aseguramos traer correctamente el detalle de la venta
$ventas = mysqli_query($conexion, "SELECT d.*, p.codproducto, p.descripcion 
                                   FROM detalle_venta d 
                                   INNER JOIN producto p ON d.id_producto = p.codproducto 
                                   WHERE d.id_venta = $id");

$pdf->Cell(195, 5, utf8_decode($datos['nombre']), 0, 1, 'C');

// Asegúrate de que la ruta de la imagen sea correcta
$pdf->Image("../../assets/img/Solo_Logo_OP.png", 180, 10, 30, 30, 'PNG');

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 5, utf8_decode("Teléfono: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(20, 5, $datos['telefono'], 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 5, utf8_decode("Dirección: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(20, 5, utf8_decode($datos['direccion']), 0, 1, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 5, "Correo: ", 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(20, 5, utf8_decode($datos['email']), 0, 1, 'L');
$pdf->Ln();
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(0, 0, 0);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(196, 5, "Datos del cliente", 1, 1, 'C', 1);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(90, 5, utf8_decode('Nombre'), 0, 0, 'L');
$pdf->Cell(50, 5, utf8_decode('Teléfono'), 0, 0, 'L');
$pdf->Cell(56, 5, utf8_decode('Dirección'), 0, 1, 'L');

$pdf->SetFont('Arial', '', 10);
$y_inicio = $pdf->GetY();
$pdf->Cell(90, 5, utf8_decode($datosC['nombre']), 0, 0, 'L');
$pdf->Cell(50, 5, utf8_decode($datosC['telefono']), 0, 0, 'L');
$pdf->MultiCell(56, 5, utf8_decode($datosC['direccion']), 0, 'L');
$y_final = $pdf->GetY();

if ($y_final > $y_inicio + 5) {
    $pdf->SetY($y_final + 2);
} else {
    $pdf->SetY($y_inicio + 5 + 2);
}

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(196, 5, "Detalle de Producto", 1, 1, 'C', 1);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(14, 5, utf8_decode('N°'), 0, 0, 'L');
$pdf->Cell(90, 5, utf8_decode('Descripción'), 0, 0, 'L');
$pdf->Cell(25, 5, 'Cantidad', 0, 0, 'L');
$pdf->Cell(32, 5, 'Precio', 0, 0, 'L');
$pdf->Cell(35, 5, 'Sub Total.', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);

$contador = 1;
$total_cantidad = 0; 
$total_neto = 0; 

// Verificamos si la consulta devolvió resultados
if (mysqli_num_rows($ventas) > 0) {
    while ($row = mysqli_fetch_assoc($ventas)) {
        $subtotal = $row['cantidad'] * $row['precio'];
        
        $pdf->Cell(14, 5, $contador, 0, 0, 'L');
        $pdf->Cell(90, 5, utf8_decode($row['descripcion']), 0, 0, 'L');
        $pdf->Cell(25, 5, $row['cantidad'], 0, 0, 'L');
        $pdf->Cell(32, 5, number_format($row['precio'], 2), 0, 0, 'L');
        $pdf->Cell(35, 5, number_format($subtotal, 2, '.', ','), 0, 1, 'L');
        
        $total_cantidad += $row['cantidad'];
        $total_neto += $subtotal;
        $contador++;
    }
} else {
    $pdf->Cell(196, 5, "No se encontraron productos para esta venta.", 0, 1, 'C');
}

$monto_iva = $total_neto * 0.16;          
$total_general = $total_neto + $monto_iva; 

$pdf->Ln(4); 
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetX(114); 
$pdf->Cell(40, 6, utf8_decode('Total Artículos:'), 1, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(42, 6, $total_cantidad, 1, 1, 'C');

$pdf->SetX(114);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(40, 6, 'I.V.A. (16%):', 1, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(42, 6, number_format($monto_iva, 2, '.', ','), 1, 1, 'R');

$pdf->SetX(114);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(40, 6, 'Total a Pagar:', 1, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(42, 6, number_format($total_general, 2, '.', ','), 1, 1, 'R');

$pdf->Ln(12);
$pdf->SetDrawColor(180, 180, 180);
$pdf->Line(40, $pdf->GetY(), 166, $pdf->GetY()); 
$pdf->Ln(3);
$pdf->SetFont('Arial', 'I', 11);
$pdf->SetTextColor(50, 50, 50);
$pdf->Cell(196, 6, utf8_decode('¡Gracias por comprar en El Sabor de OP, esperamos que regreses!'), 0, 1, 'C');
$pdf->Ln(3);
$pdf->Line(40, $pdf->GetY(), 166, $pdf->GetY());

$pdf->Output("ventas.pdf", "I");
?>