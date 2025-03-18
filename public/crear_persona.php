<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once '../includes/conexion.php';
require_once '../includes/funciones.php';

// Si hay un envío de formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario y procesarlos
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $sexo = $_POST['sexo'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $departamento = $_POST['departamento'];
    $municipio = $_POST['municipio'];

    // Insertar en la base de datos
    $stmt = $pdo->prepare("INSERT INTO personas (nombres, apellidos, sexo, fecha_nacimiento, departamento, municipio) 
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nombres, $apellidos, $sexo, $fecha_nacimiento, $departamento, $municipio]);

    // Redirigir a la página de listado
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
    <title>Crear Persona</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <!-- Incluyendo el sidebar -->
        <?php include '../includes/sidebar.php'; ?>

        <!-- Contenido principal -->
        <div class="main-content">
            <h2>Crear Persona</h2>
            
            <form method="POST" action="">
                <!-- Row para los campos -->
                <div class="form-row">
                    <div class="form-column">
                        <label for="nombres">Nombres</label>
                        <input type="text" name="nombres" required>
                    </div>
                    <div class="form-column">
                        <label for="apellidos">Apellidos</label>
                        <input type="text" name="apellidos" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-column">
                        <label for="sexo">Sexo</label>
                        <select name="sexo" required>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                        </select>
                    </div>
                    <div class="form-column">
                        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" required id="fecha_nacimiento">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-column">
                        <label for="departamento">Departamento</label>
                        <select name="departamento" id="departamento" required>
                            <option value="">Seleccione un Departamento</option>
                            <?php foreach ($departamentos as $departamento): ?>
                                <option value="<?php echo $departamento; ?>"><?php echo $departamento; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-column">
                        <label for="municipio">Municipio</label>
                        <select name="municipio" id="municipio" required>
                            <option value="">Seleccione un Municipio</option>
                        </select>
                    </div>
                </div>

                <button type="submit">Guardar</button>
            </form>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Establecer la fecha máxima en el campo de fecha de nacimiento
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); // Enero es 0!
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;
            $('#fecha_nacimiento').attr('max', today); // Limitar la fecha máxima

            // Función para actualizar el municipio según el departamento seleccionado
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
            });

            // Función para validar la fecha de nacimiento
            $('form').submit(function(event) {
                var fechaNacimiento = $('input[name="fecha_nacimiento"]').val();
                var fechaActual = new Date();
                var fechaNacimientoDate = new Date(fechaNacimiento);

                // Compara la fecha de nacimiento con la fecha actual
                if (fechaNacimientoDate > fechaActual) {
                    alert("La fecha de nacimiento no puede ser mayor que la fecha actual.");
                    event.preventDefault(); // Evita que se envíe el formulario
                }
            });
        });
    </script>
</body>
</html>
