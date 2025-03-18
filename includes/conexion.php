<?php
// Cargar las variables de entorno desde el archivo .env
require_once __DIR__ . '/../vendor/autoload.php'; // Ajusta la ruta si es necesario
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
// Conexion a la base de datos MySQL usando PDO
$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];
$port = $_ENV['DB_PORT'];
try {
    // Establecer la conexión con el puerto
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conexión exitosa!";
} catch (PDOException $e) {
    // Si hay un error, lo mostramos
    die("Error al conectar a la base de datos: " . $e->getMessage());
}
?>
