<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../includes/conexion.php';
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: index.php');
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/estilos.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <form action="login.php" method="POST" id="loginForm">
            <h2>Iniciar sesión</h2>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
            <input type="text" name="username" id="username" placeholder="Username" required>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <button type="submit">Entrar</button>
        </form>
        <p>No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
    </div>

    <script>
        $(document).ready(function() {
            $('#loginForm').submit(function(e) {
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
