<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/conexion.php';

// Verificar si se pasó un ID por GET
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $persona_id = $_GET['id'];

    // Preparar la consulta para eliminar la persona de la base de datos
    $stmt = $pdo->prepare("DELETE FROM personas WHERE id = ?");
    $stmt->execute([$persona_id]);

    // Redirigir al listado de personas después de eliminar
    header('Location: index.php');
    exit();
} else {
    // Si no se pasa un ID válido, redirigir a la página principal
    header('Location: index.php');
    exit();
}
?>
