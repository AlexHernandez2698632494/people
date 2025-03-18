<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/conexion.php';
require_once '../includes/funciones.php';

$personas = obtenerPersonas($pdo);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Personas</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="sidebar">
        <ul>
            <li><a href="index.php">Listar Personas</a></li>
            <li><a href="crear_persona.php">Crear Persona</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2>Listado de Personas</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Sexo</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Departamento</th>
                    <th>Municipio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($personas as $persona): ?>
                    <tr>
                        <td><?php echo $persona['id']; ?></td>
                        <td><?php echo $persona['nombres']; ?></td>
                        <td><?php echo $persona['apellidos']; ?></td>
                        <td><?php echo $persona['sexo']; ?></td>
                        <td><?php echo $persona['fecha_nacimiento']; ?></td>
                        <td><?php echo $persona['departamento']; ?></td>
                        <td><?php echo $persona['municipio']; ?></td>
                        <td>
                            <a href="editar_persona.php?id=<?php echo $persona['id']; ?>" class="editar">Editar</a>
                            <a href="eliminar_persona.php?id=<?php echo $persona['id']; ?>" class="eliminar">Eliminar</a>
                            <a href="generar_pdf.php?id=<?php echo $persona['id']; ?>" class="generarpdf">Generar PDF</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
