<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../includes/conexion.php';
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $password]);

    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="stylesheet" href="../assets/css/estilos.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
    <form action="register.php" method="POST" id="registerForm">
        <h2>Registrar usuario</h2>
        
        <input type="text" name="username" id="username" placeholder="Username" required>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <button type="submit">Registrar</button>
    </form>

    <p>Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
    </div>

    <script>
        $(document).ready(function() {
            $('#registerForm').submit(function(e) {
                var username = $('#username').val();
                var password = $('#password').val();
                
                // Validación de campos vacíos
                if (username === '' || password === '') {
                    alert('Por favor, complete todos los campos.');
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
