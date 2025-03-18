<?php
session_start();  // Iniciar la sesión (si no está iniciada)

session_unset();  // Elimina todas las variables de sesión
session_destroy();  // Destruye la sesión

// Redirigir al usuario a la página de inicio de sesión (o cualquier página que desees)
header('Location: login.php');
exit();
?>
