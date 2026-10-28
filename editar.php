<?php
require_once 'includes/seguridad.php';
requiereLogin();
require_once 'conexion.php';
require_once 'includes/helpers.php';

$id = (int) ($_GET['id'] ?? 0);
try {
    $stmt = $conn->prepare("SELECT * FROM prestamos WHERE id_prestamo = :id");
    $stmt->execute([':id' => $id]);
    $p = $stmt->fetch();
} catch (PDOException $e) {
    errorBaseDatos($e);
}

if (!$p) {
    header('Location: listar.php?msg=error');
    exit;
}

$titulo = 'Editar préstamo';
$activo = 'listar';
require 'includes/header.php';
?>

<header class="masthead masthead-pagina masthead-edicion text-white">
    <div class="masthead-content">
        <div class="container px-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-light mb-2">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="listar.php">Listado</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar #<?= $id ?></li>
                </ol>
            </nav>
            <h1 class="masthead-heading mb-1">Editar préstamo #<?= $id ?></h1>
            <p class="masthead-lead mb-0">Modifica los datos necesarios y guarda los cambios.</p>
        </div>
    </div>
    <?= circulos() ?>
</header>

<section class="container px-3 px-sm-4 px-lg-5 page-body">
    <div class="row justify-content-center g-4">
        <div class="col-lg-4 order-lg-2">
            <div class="card info-card">
                <div class="card-body p-4">
                    <h6 class="text-uppercase small fw-bold text-muted mb-3">Registro actual</h6>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="avatar avatar-lg"><?= iniciales($p['nombre_socio']) ?></span>
                        <div>
                            <div class="fw-semibold"><?= htmlspecialchars($p['nombre_socio']) ?></div>
                            <div class="small text-muted"><?= htmlspecialchars($p['codigo_socio']) ?></div>
                        </div>
                    </div>
                    <ul class="list-unstyled small mb-3 info-list">
                        <li><i class="bi bi-book"></i><?= htmlspecialchars($p['titulo_libro']) ?></li>
                        <li><i class="bi bi-pen"></i><?= htmlspecialchars($p['autor_libro']) ?></li>
                        <li><i class="bi bi-calendar3"></i><?= date('d/m/Y', strtotime($p['fecha_prestamo'])) ?></li>
                    </ul>
                    <?= badgeEstado($p['estado_prestamo']) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-8 order-lg-1">
            <div class="card form-card-bs">
                <div class="card-body p-4 p-md-5">
                    <form action="actualizar.php" method="post" class="needs-validation" novalidate>
                        <input type="hidden" name="id_prestamo" value="<?= $id ?>">
                        <?php require 'includes/form_prestamo.php'; ?>

                        <div class="d-flex flex-column flex-sm-row gap-3 mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill flex-fill">Guardar cambios</button>
                            <a href="listar.php" class="btn btn-outline-dark btn-lg rounded-pill flex-fill">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require 'includes/footer.php'; ?>
