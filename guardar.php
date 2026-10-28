<?php
require_once 'includes/seguridad.php';
requiereLogin();
require_once 'conexion.php';
require_once 'includes/helpers.php';
$success = false;
$message = "Acceso denegado";
$nuevoId = null;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    verificarCsrf();
    $datos = datosFormulario();
    $errores = validarPrestamo($datos);
    if ($errores) {
        $message = "No se guardó el préstamo. " . implode(' ', $errores);
    } else {
        try {
            $sql = "INSERT INTO prestamos (estado_prestamo, codigo_socio, nombre_socio,
                    titulo_libro, autor_libro, correo_socio, isbn, fecha_prestamo)
                    VALUES (:estado_prestamo, :codigo_socio, :nombre_socio, :titulo_libro,
                    :autor_libro, :correo_socio, :isbn, :fecha_prestamo)";
            $stmt = $conn->prepare($sql);
            $stmt->execute($datos);
            $nuevoId = $conn->lastInsertId();
            $success = true;
            $message = "El préstamo se registró correctamente";
        } catch (PDOException $e) {
            error_log('guardar.php: ' . $e->getMessage());
            $message = "No se pudo guardar el préstamo. Inténtalo de nuevo.";
        }
    }
}

$titulo = 'Resultado';
$activo = 'registrar';
require 'includes/header.php';
?>

<header class="masthead masthead-pagina text-white text-center">
    <div class="masthead-content">
        <div class="container px-5">
            <h1 class="masthead-heading mb-1">Registro de préstamo</h1>
            <p class="masthead-lead mb-0">Resultado de la operación en la base de datos.</p>
        </div>
    </div>
    <?= circulos() ?>
</header>

<section class="container px-3 px-sm-4 px-lg-5 page-body">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card status-card-bs text-center">
                <div class="card-body p-5">
                    <div class="status-icon <?= $success ? 'ok' : 'error' ?>">
                        <i class="bi <?= $success ? 'bi-check-lg' : 'bi-x-lg' ?>"></i>
                    </div>
                    <h2 class="mt-4 mb-2"><?= $success ? '¡Éxito!' : 'Atención' ?></h2>
                    <p class="text-muted mb-4"><?= htmlspecialchars($message) ?></p>
                    <?php if ($success): ?>
                        <div class="resumen-box text-start mb-4">
                            <div><i class="bi bi-hash me-2"></i><strong>ID del préstamo:</strong> <?= htmlspecialchars($nuevoId) ?></div>
                            <div><i class="bi bi-person me-2"></i><strong>Socio:</strong> <?= htmlspecialchars($_POST['nombre_socio']) ?></div>
                            <div><i class="bi bi-book me-2"></i><strong>Libro:</strong> <?= htmlspecialchars($_POST['titulo_libro']) ?></div>
                        </div>
                    <?php endif; ?>
                    <div class="d-grid gap-3 col-sm-10 mx-auto">
                        <a class="btn btn-primary btn-lg rounded-pill" href="listar.php?msg=registrado&id=<?= urlencode($nuevoId ?? '') ?>">Ver en el listado</a>
                        <a class="btn btn-outline-dark btn-lg rounded-pill" href="registrar.php">Volver al formulario</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require 'includes/footer.php'; ?>
