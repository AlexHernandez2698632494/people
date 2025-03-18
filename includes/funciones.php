<?php
// Función para obtener todas las personas
function obtenerPersonas($pdo) {
    $stmt = $pdo->query("SELECT * FROM personas");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerPersonaPorId($pdo, $id) {
    $sql = "SELECT * FROM personas WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


// Función para guardar una persona
function guardarPersona($pdo, $nombres, $apellidos, $sexo, $fecha_nacimiento, $departamento, $municipio) {
    $sql = "INSERT INTO personas (nombres, apellidos, sexo, fecha_nacimiento, departamento, municipio) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombres, $apellidos, $sexo, $fecha_nacimiento, $departamento, $municipio]);
}

// Función para eliminar una persona
function eliminarPersona($pdo, $id) {
    $sql = "DELETE FROM personas WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
}
?>
