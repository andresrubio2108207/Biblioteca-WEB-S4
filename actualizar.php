<?php
require_once 'includes/seguridad.php';
requiereLogin();
require_once 'conexion.php';
require_once 'includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header('Location: listar.php');
    exit;
}
verificarCsrf();

$id = (int) ($_POST['id_prestamo'] ?? 0);
$datos = datosFormulario();
if (validarPrestamo($datos)) {
    header("Location: listar.php?msg=invalido&id=$id");
    exit;
}

try {
    $sql = "UPDATE prestamos SET estado_prestamo = :estado_prestamo, codigo_socio = :codigo_socio,
            nombre_socio = :nombre_socio, titulo_libro = :titulo_libro, autor_libro = :autor_libro,
            correo_socio = :correo_socio, isbn = :isbn, fecha_prestamo = :fecha_prestamo
            WHERE id_prestamo = :id_prestamo";
    $stmt = $conn->prepare($sql);
    $stmt->execute($datos + [':id_prestamo' => $id]);
    header("Location: listar.php?msg=editado&id=$id");
} catch (PDOException $e) {
    error_log('actualizar.php: ' . $e->getMessage());
    header("Location: listar.php?msg=error");
}
exit;
