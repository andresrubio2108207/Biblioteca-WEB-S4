<?php
require_once 'conexion.php';
try {
    $sql = "SELECT id_prestamo, estado_prestamo, codigo_socio, nombre_socio,
            titulo_libro, autor_libro, correo_socio, isbn, fecha_prestamo
            FROM prestamos";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $prestamos = $stmt->fetchAll();
} catch (PDOException $e) {
    die("El error es " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Prestamos</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Listado de Prestamos</h2>
            <a href="registrar.php" class="btn-nuevo">+ Nuevo Prestamo</a>
        </div>

        <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Estado</th>
                    <th>Codigo de socio</th>
                    <th>Nombre del socio</th>
                    <th>Titulo del libro</th>
                    <th>Autor</th>
                    <th>Correo</th>
                    <th>ISBN</th>
                    <th>Fecha de prestamo</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($prestamos) > 0): ?>
                    <?php foreach ($prestamos as $prestamo): ?>
                        <tr>
                            <td><?= htmlspecialchars($prestamo['id_prestamo']) ?></td>
                            <td><?= htmlspecialchars($prestamo['estado_prestamo']) ?></td>
                            <td><?= htmlspecialchars($prestamo['codigo_socio']) ?></td>
                            <td><?= htmlspecialchars($prestamo['nombre_socio']) ?></td>
                            <td><?= htmlspecialchars($prestamo['titulo_libro']) ?></td>
                            <td><?= htmlspecialchars($prestamo['autor_libro']) ?></td>
                            <td><?= htmlspecialchars($prestamo['correo_socio']) ?></td>
                            <td><?= htmlspecialchars($prestamo['isbn']) ?></td>
                            <td><?= htmlspecialchars($prestamo['fecha_prestamo']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="sin-datos">No hay prestamos registrados</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>
</body>
</html>
