i<?php
require_once('../includes/fpdf/fpdf.php');
require_once('../includes/conexion.php');
require_once('../includes/funciones.php');

// Verificar que se haya enviado el id
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener la información de la persona con la función de la base de datos
    $persona = obtenerPersonaPorId($pdo, $id);

    if ($persona) {
        // Crear una instancia de la clase FPDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Establecer márgenes
        $pdf->SetMargins(10, 10, 10);

        // Título: Establecer fuente, tamaño y color
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTextColor(0, 102, 204); // Azul
        $pdf->Cell(200, 10, 'Datos de la Persona', 0, 1, 'C');
        
        // Salto de línea
        $pdf->Ln(10);

        // Establecer fuente para el contenido
        $pdf->SetFont('Arial', '', 12);

        // Establecer color de texto para el contenido
        $pdf->SetTextColor(0, 0, 0); // Negro

        // Agregar los detalles de la persona con celdas con bordes

        // Cambiamos Cell por MultiCell para permitir el salto de línea si el contenido es largo
        $pdf->Cell(50, 10, 'ID:', 1, 0, 'L', false);
        $pdf->Cell(0, 10, $persona['id'], 1, 1, 'L');

        $pdf->Cell(50, 10, 'Nombres:', 1, 0, 'L', false);
        $pdf->MultiCell(0, 10, $persona['nombres'], 1, 'L');

        $pdf->Cell(50, 10, 'Apellidos:', 1, 0, 'L', false);
        $pdf->MultiCell(0, 10, $persona['apellidos'], 1, 'L');

        $pdf->Cell(50, 10, 'Sexo:', 1, 0, 'L', false);
        $pdf->Cell(0, 10, $persona['sexo'], 1, 1, 'L');

        $pdf->Cell(50, 10, 'Fecha de Nacimiento:', 1, 0, 'L', false);
        $pdf->Cell(0, 10, $persona['fecha_nacimiento'], 1, 1, 'L');

        $pdf->Cell(50, 10, 'Departamento:', 1, 0, 'L', false);
        $pdf->Cell(0, 10, $persona['departamento'], 1, 1, 'L');

        $pdf->Cell(50, 10, 'Municipio:', 1, 0, 'L', false);
        $pdf->Cell(0, 10, $persona['municipio'], 1, 1, 'L');

        // Agregar un pie de página
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->SetTextColor(128, 128, 128); // Gris
        $pdf->Cell(0, 10, 'Generado por el sistema', 0, 0, 'C');

        // Salvar el archivo PDF
        $pdf->Output('I', 'persona_' . $persona['id'] . '.pdf');
    } else {
        echo 'Persona no encontrada.';
    }
} else {
    echo 'ID no proporcionado.';
}
?>
