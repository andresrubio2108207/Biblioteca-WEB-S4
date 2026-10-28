<?php
require_once 'conexion.php';
$success = false;
$message = "Acceso denegado";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $estado_prestamo = $_POST['estado_prestamo'];
    $codigo_socio = $_POST['codigo_socio'];
    $nombre_socio = $_POST['nombre_socio'];
    $titulo_libro = $_POST['titulo_libro'];
    $autor_libro = $_POST['autor_libro'];
    $correo_socio = $_POST['correo_socio'];
    $isbn = $_POST['isbn'];
    $fecha_prestamo = $_POST['fecha_prestamo'];

    try {
        $sql = "INSERT INTO prestamos (estado_prestamo, codigo_socio, nombre_socio,
                titulo_libro, autor_libro, correo_socio, isbn, fecha_prestamo)
                VALUES (:estado_prestamo, :codigo_socio, :nombre_socio, :titulo_libro,
                :autor_libro, :correo_socio, :isbn, :fecha_prestamo)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':estado_prestamo' => $estado_prestamo,
            ':codigo_socio' => $codigo_socio,
            ':nombre_socio' => $nombre_socio,
            ':titulo_libro' => $titulo_libro,
            ':autor_libro' => $autor_libro,
            ':correo_socio' => $correo_socio,
            ':isbn' => $isbn,
            ':fecha_prestamo' => $fecha_prestamo
        ]);
        $success = true;
        $message = "El prestamo se registró correctamente";
    } catch (PDOException $e) {
        $message = "Error al guardar el prestamo: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="page-shell">
        <div class="status-card">
            <h3><?= htmlspecialchars($success ? '¡Éxito!' : 'Atención'); ?></h3>
            <p><?= htmlspecialchars($message); ?></p>
            <a class="btn-link" href="registrar.php">Volver al formulario</a>
        </div>
    </div>
</body>
</html>
