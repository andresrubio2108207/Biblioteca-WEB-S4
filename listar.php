<?php
require_once 'includes/seguridad.php';
requiereLogin();
require_once 'conexion.php';
require_once 'includes/helpers.php';
try {
    $sql = "SELECT id_prestamo, estado_prestamo, codigo_socio, nombre_socio,
            titulo_libro, autor_libro, correo_socio, isbn, fecha_prestamo
            FROM prestamos ORDER BY id_prestamo";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $prestamos = $stmt->fetchAll();
} catch (PDOException $e) {
    errorBaseDatos($e);
}

// Mensajes que llegan después de registrar, editar o eliminar
$mensajes = [
    'registrado' => ['success', 'bi-check-circle-fill', 'Préstamo registrado', 'El nuevo préstamo ya aparece en el listado.'],
    'editado'    => ['success', 'bi-check-circle-fill', '¡Registro editado satisfactoriamente!', 'Los cambios del préstamo se guardaron en la base de datos. El registro actualizado está resaltado en la tabla.'],
    'eliminado'  => ['warning', 'bi-trash3-fill', 'Préstamo eliminado', 'El registro se eliminó de la base de datos.'],
    'error'      => ['danger', 'bi-x-octagon-fill', 'No se pudo completar la acción', 'Verifica los datos e inténtalo de nuevo.'],
    'invalido'   => ['danger', 'bi-x-octagon-fill', 'Datos no válidos', 'Los cambios no se guardaron porque algún campo no cumple el formato. Revisa el correo, el ISBN, la fecha y el estado.'],
];
$msg = $mensajes[$_GET['msg'] ?? ''] ?? null;
$resaltado = (int) ($_GET['id'] ?? 0);

$busqueda = $_GET['q'] ?? '';
$conteo = array_count_values(array_column($prestamos, 'estado_prestamo'));

$titulo = 'Listado de préstamos';
$activo = 'listar';
require 'includes/header.php';
?>

<header class="masthead masthead-pagina text-white">
    <div class="masthead-content">
        <div class="container px-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-light mb-2">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Listado</li>
                </ol>
            </nav>
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <h1 class="masthead-heading mb-1">Listado de préstamos</h1>
                    <p class="masthead-lead mb-0"><?= count($prestamos) ?> préstamos registrados en la biblioteca.</p>
                </div>
                <a href="registrar.php" class="btn btn-light btn-lg rounded-pill text-primary">Nuevo préstamo</a>
            </div>
        </div>
    </div>
    <?= circulos() ?>
</header>

<section class="container px-3 px-sm-4 px-lg-5 page-body">
    <?php if ($msg): ?>
        <div class="alert alert-<?= $msg[0] ?> alert-dismissible fade show alert-biblio d-flex align-items-start gap-3" role="alert" id="alerta">
            <i class="bi <?= $msg[1] ?> fs-3"></i>
            <div>
                <h5 class="alert-heading mb-1"><?= $msg[2] ?></h5>
                <p class="mb-0"><?= $msg[3] ?><?= $resaltado ? ' (ID #' . $resaltado . ')' : '' ?></p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    <div class="card table-card">
        <div class="card-header bg-white border-0 p-4">
            <div class="row g-3 align-items-center">
                <div class="col-xl-4">
                    <div class="input-group search-box">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="search" id="buscar" class="form-control" placeholder="Buscar por socio, libro, autor o ISBN..." value="<?= htmlspecialchars($busqueda) ?>">
                    </div>
                </div>
                <div class="col-xl-8">
                    <div class="d-flex flex-wrap gap-2 justify-content-xl-end" id="filtros">
                        <button class="btn btn-filtro rounded-pill active" data-estado="">Todos <span class="badge rounded-pill"><?= count($prestamos) ?></span></button>
                        <?php foreach (['Prestado', 'Devuelto', 'Atrasado', 'Reservado'] as $e): ?>
                            <button class="btn btn-filtro rounded-pill" data-estado="<?= $e ?>"><?= $e ?> <span class="badge rounded-pill"><?= $conteo[$e] ?? 0 ?></span></button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-biblio align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Socio</th>
                        <th>Libro</th>
                        <th>ISBN</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($prestamos) > 0): ?>
                        <?php foreach ($prestamos as $prestamo): ?>
                            <tr data-estado="<?= htmlspecialchars($prestamo['estado_prestamo']) ?>" class="<?= $resaltado === (int) $prestamo['id_prestamo'] ? 'fila-resaltada' : '' ?>">
                                <td class="fw-semibold text-muted">#<?= htmlspecialchars($prestamo['id_prestamo']) ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="avatar"><?= iniciales($prestamo['nombre_socio']) ?></span>
                                        <div>
                                            <div class="fw-semibold"><?= htmlspecialchars($prestamo['nombre_socio']) ?></div>
                                            <div class="small text-muted"><?= htmlspecialchars($prestamo['codigo_socio']) ?> · <?= htmlspecialchars($prestamo['correo_socio']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="celda-libro">
                                    <div class="d-flex align-items-center gap-3">
                                        <?= portada($prestamo['isbn'], $prestamo['titulo_libro'], $prestamo['autor_libro'], 'portada-xs') ?>
                                        <div>
                                            <div class="fw-semibold libro-titulo"><?= htmlspecialchars($prestamo['titulo_libro']) ?></div>
                                            <div class="small text-muted"><?= htmlspecialchars($prestamo['autor_libro']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="small font-monospace"><?= htmlspecialchars($prestamo['isbn']) ?></td>
                                <td class="small text-nowrap"><i class="bi bi-calendar3 me-1 text-muted"></i><?= date('d/m/Y', strtotime($prestamo['fecha_prestamo'])) ?></td>
                                <td><?= badgeEstado($prestamo['estado_prestamo']) ?></td>
                                <td class="text-end text-nowrap">
                                    <a href="editar.php?id=<?= urlencode($prestamo['id_prestamo']) ?>" class="btn btn-sm btn-accion btn-editar rounded-pill" title="Editar"><i class="bi bi-pencil-square"></i> Editar</a>
                                    <button type="button" class="btn btn-sm btn-accion btn-eliminar rounded-pill" title="Eliminar"
                                        data-bs-toggle="modal" data-bs-target="#modalEliminar"
                                        data-id="<?= htmlspecialchars($prestamo['id_prestamo']) ?>"
                                        data-libro="<?= htmlspecialchars($prestamo['titulo_libro']) ?>"><i class="bi bi-trash3"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <tr id="sinResultados" class="<?= count($prestamos) > 0 ? 'd-none' : '' ?>">
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>No hay préstamos para mostrar
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" action="eliminar.php" method="post">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="modalEliminarLabel"><i class="bi bi-exclamation-triangle text-danger me-2"></i>Eliminar préstamo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                ¿Seguro que deseas eliminar el préstamo de <strong id="libroEliminar"></strong>? Esta acción no se puede deshacer.
                <input type="hidden" name="id_prestamo" id="idEliminar">
                <?= campoCsrf() ?>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger rounded-pill px-4"><i class="bi bi-trash3 me-1"></i>Eliminar</button>
            </div>
        </form>
    </div>
</div>

<?php
$script = 'js/listar.js';
require 'includes/footer.php';
?>
