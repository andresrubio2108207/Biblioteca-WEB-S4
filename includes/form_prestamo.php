<?php
// Campos del formulario de préstamo, compartidos por registrar.php y editar.php.
// $p contiene los valores actuales (vacío al registrar, datos del registro al editar).
$p = $p ?? [];
$v = fn($campo) => htmlspecialchars($p[$campo] ?? '');
$estados = [
    'Prestado'  => 'bi-arrow-up-right-circle',
    'Devuelto'  => 'bi-check2-circle',
    'Atrasado'  => 'bi-exclamation-octagon',
    'Reservado' => 'bi-bookmark-star',
];
?>
<?= campoCsrf() ?>
<h6 class="form-section-title"><i class="bi bi-person-badge"></i> Datos del socio</h6>
<div class="row g-3 mb-4">
    <div class="col-md-5">
        <label for="codigo_socio" class="form-label">Código de socio</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
            <input type="text" class="form-control" id="codigo_socio" name="codigo_socio" maxlength="15" placeholder="SOC-000" value="<?= $v('codigo_socio') ?>" required>
            <div class="invalid-feedback">Ingresa el código del socio.</div>
        </div>
    </div>
    <div class="col-md-7">
        <label for="nombre_socio" class="form-label">Nombre del socio</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" class="form-control" id="nombre_socio" name="nombre_socio" maxlength="40" placeholder="Nombre completo" value="<?= $v('nombre_socio') ?>" required>
            <div class="invalid-feedback">Ingresa el nombre del socio.</div>
        </div>
    </div>
    <div class="col-12">
        <label for="correo_socio" class="form-label">Correo del socio</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" class="form-control" id="correo_socio" name="correo_socio" maxlength="60" placeholder="socio@correo.com" value="<?= $v('correo_socio') ?>" required>
            <div class="invalid-feedback">Ingresa un correo válido.</div>
        </div>
    </div>
</div>

<h6 class="form-section-title"><i class="bi bi-book"></i> Datos del libro</h6>
<div class="row g-3 mb-4">
    <div class="col-md-7">
        <label for="titulo_libro" class="form-label">Título del libro</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-bookmark"></i></span>
            <input type="text" class="form-control" id="titulo_libro" name="titulo_libro" maxlength="80" placeholder="Ej. Cien años de soledad" value="<?= $v('titulo_libro') ?>" required>
            <div class="invalid-feedback">Ingresa el título.</div>
        </div>
    </div>
    <div class="col-md-5">
        <label for="autor_libro" class="form-label">Autor</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-pen"></i></span>
            <input type="text" class="form-control" id="autor_libro" name="autor_libro" maxlength="40" placeholder="Autor" value="<?= $v('autor_libro') ?>" required>
            <div class="invalid-feedback">Ingresa el autor.</div>
        </div>
    </div>
    <div class="col-md-6">
        <label for="isbn" class="form-label">ISBN</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-hash"></i></span>
            <input type="text" class="form-control" id="isbn" name="isbn" maxlength="20" placeholder="978..." value="<?= $v('isbn') ?>" required>
            <div class="invalid-feedback">Ingresa el ISBN.</div>
        </div>
    </div>
    <div class="col-md-6">
        <label for="fecha_prestamo" class="form-label">Fecha del préstamo</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
            <input type="date" class="form-control" id="fecha_prestamo" name="fecha_prestamo" value="<?= $v('fecha_prestamo') ?>" required>
            <div class="invalid-feedback">Selecciona la fecha.</div>
        </div>
    </div>
</div>

<h6 class="form-section-title"><i class="bi bi-flag"></i> Estado del préstamo</h6>
<div class="estado-grid mb-2">
    <?php foreach ($estados as $estado => $icono): ?>
        <input type="radio" class="btn-check" name="estado_prestamo" id="estado_<?= $estado ?>" value="<?= $estado ?>" <?= ($p['estado_prestamo'] ?? '') === $estado ? 'checked' : '' ?> required>
        <label class="btn btn-estado estado-<?= strtolower($estado) ?>" for="estado_<?= $estado ?>">
            <i class="bi <?= $icono ?> d-block fs-4 mb-1"></i><?= $estado ?>
        </label>
    <?php endforeach; ?>
</div>
