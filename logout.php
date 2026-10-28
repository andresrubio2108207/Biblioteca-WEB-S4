<?php
require_once 'includes/seguridad.php';

// Se cierra sesión solo por POST con token, para que otra página no pueda sacar al usuario
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}
verificarCsrf();

$_SESSION = [];
session_destroy();
setcookie(session_name(), '', ['expires' => time() - 3600, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax']);
header('Location: login.php?salio=1');
exit;
