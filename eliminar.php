<?php
require_once 'includes/seguridad.php';
requiereLogin();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    verificarCsrf();
    try {
        $stmt = $conn->prepare("DELETE FROM prestamos WHERE id_prestamo = :id");
        $stmt->execute([':id' => (int) ($_POST['id_prestamo'] ?? 0)]);
        header('Location: listar.php?msg=eliminado');
    } catch (PDOException $e) {
        error_log('eliminar.php: ' . $e->getMessage());
        header('Location: listar.php?msg=error');
    }
} else {
    header('Location: listar.php');
}
exit;
