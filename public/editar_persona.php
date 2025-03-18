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

    // Obtener la persona de la base de datos
    $stmt = $pdo->prepare("SELECT * FROM personas WHERE id = ?");
    $stmt->execute([$persona_id]);
    $persona = $stmt->fetch();

    // Si la persona no existe, redirigir al listado
    if (!$persona) {
        header('Location: index.php');
        exit();
    }

    // Si el formulario se ha enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener los datos del formulario
        $nombres = $_POST['nombres'];
        $apellidos = $_POST['apellidos'];
        $sexo = $_POST['sexo'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $departamento = $_POST['departamento'];
        $municipio = $_POST['municipio'];

        // Actualizar los datos de la persona en la base de datos
        $stmt = $pdo->prepare("UPDATE personas SET nombres = ?, apellidos = ?, sexo = ?, fecha_nacimiento = ?, departamento = ?, municipio = ? WHERE id = ?");
        $stmt->execute([$nombres, $apellidos, $sexo, $fecha_nacimiento, $departamento, $municipio, $persona_id]);

        // Redirigir a la página de listado después de actualizar
        header('Location: index.php');
        exit();
    }
} else {
    // Si no se pasó un ID válido, redirigir al listado
    header('Location: index.php');
    exit();
}

// Lista de departamentos de El Salvador
$departamentos = [
    'Ahuachapán', 'Cabañas', 'Chalatenango', 'Cuscatlán', 'La Libertad', 
    'La Paz', 'La Unión', 'Morazán', 'San Miguel', 'San Salvador', 
    'San Vicente', 'Santa Ana', 'Sonsonate', 'Usulután'
];

// Lista de los 44 municipios (según la nueva reestructuración)
$municipios = [
    'Ahuachapán' => ['Ahuachapán', 'Conchagua', 'San Lorenzo'],
    'Cabañas' => ['Sensuntepeque', 'Ilobasco', 'Candelaria', 'Cinquera'],
    'Chalatenango' => ['Chalatenango', 'La Palma', 'San Ignacio'],
    'Cuscatlán' => ['Cojutepeque', 'El Rosario'],
    'La Libertad' => ['Santa Tecla', 'Zaragoza', 'Chiltiupán'],
    'La Paz' => ['Zacatecoluca', 'San Pedro Masahuat'],
    'La Unión' => ['La Unión', 'Conchagua', 'El Carmen'],
    'Morazán' => ['San Francisco Gotera', 'Perquín'],
    'San Miguel' => ['San Miguel', 'Comalapa', 'Chinameca'],
    'San Salvador' => ['San Salvador', 'Soyapango', 'Mejicanos'],
    'San Vicente' => ['San Vicente', 'Apastepeque'],
    'Santa Ana' => ['Santa Ana', 'Chalchuapa'],
    'Sonsonate' => ['Sonsonate', 'Juayúa', 'Izalco'],
    'Usulután' => ['Usulután', 'El Triunfo'],
    // Aquí añades el resto de municipios para cada departamento
];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Persona</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <!-- Incluyendo el sidebar -->
        <?php include '../includes/sidebar.php'; ?>

        <!-- Contenido principal -->
        <div class="main-content">
            <h2>Editar Persona</h2>
            
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-column">
                        <label for="nombres">Nombres</label>
                        <input type="text" name="nombres" value="<?php echo htmlspecialchars($persona['nombres']); ?>" required>
                    </div>
                    <div class="form-column">
                        <label for="apellidos">Apellidos</label>
                        <input type="text" name="apellidos" value="<?php echo htmlspecialchars($persona['apellidos']); ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-column">
                        <label for="sexo">Sexo</label>
                        <select name="sexo" required>
                            <option value="Masculino" <?php echo $persona['sexo'] === 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                            <option value="Femenino" <?php echo $persona['sexo'] === 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
                        </select>
                    </div>
                    <div class="form-column">
                        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" value="<?php echo $persona['fecha_nacimiento']; ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-column">
                        <label for="departamento">Departamento</label>
                        <select name="departamento" id="departamento" required>
                            <option value="">Seleccione un Departamento</option>
                            <?php foreach ($departamentos as $departamento): ?>
                                <option value="<?php echo $departamento; ?>" <?php echo $departamento === $persona['departamento'] ? 'selected' : ''; ?>><?php echo $departamento; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-column">
                        <label for="municipio">Municipio</label>
                        <select name="municipio" id="municipio" required>
                            <!-- Los municipios se cargarán dinámicamente con jQuery -->
                        </select>
                    </div>
                </div>

                <button type="submit">Actualizar</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Actualización del municipio según el departamento seleccionado
        $('#departamento').change(function() {
            var departamento = $(this).val();
            var municipios = <?php echo json_encode($municipios); ?>;
            var options = '<option value="">Seleccione un Municipio</option>';

            // Verifica si el departamento tiene municipios
            if (departamento && municipios[departamento]) {
                // Recorrer los municipios del departamento seleccionado
                $.each(municipios[departamento], function(index, municipio) {
                    options += '<option value="' + municipio + '">' + municipio + '</option>';
                });
            }

            // Actualiza el select de municipios
            $('#municipio').html(options);

            // Si el municipio de la persona ya está seleccionado, mantenerlo seleccionado
            var municipioActual = '<?php echo $persona['municipio']; ?>';
            if (municipioActual) {
                $('#municipio').val(municipioActual);
            }
        });

        // Disparar el evento de cambio para cargar los municipios al cargar la página
        $(document).ready(function() {
            $('#departamento').change();
        });
    </script>
</body>
</html>
