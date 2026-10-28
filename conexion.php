<?php
require_once __DIR__ . '/includes/seguridad.php';

// Usuario propio de la aplicación: solo puede leer y modificar las tablas de la biblioteca
$host = "localhost";
$db = "biblioteca_prestamos";
$user = "biblioteca_app";
$pass = "mL7FdKDWFqVcL7rTCx1Jzt3V";
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    // Modo estricto: MySQL rechaza fechas inválidas y textos demasiado largos en vez de recortarlos
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET SESSION sql_mode = 'STRICT_ALL_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'",
];

try {
    $conn = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    errorBaseDatos($e);
}
