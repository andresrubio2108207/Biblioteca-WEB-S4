<?php
require_once 'includes/seguridad.php';
requiereLogin();
$p = [
    'titulo_libro'   => $_GET['libro'] ?? '',
    'autor_libro'    => $_GET['autor'] ?? '',
    'isbn'           => $_GET['isbn'] ?? '',
    'fecha_prestamo' => date('Y-m-d'),
];
$titulo = 'Registrar préstamo';
$activo = 'registrar';
require 'includes/header.php';
?>

<header class="masthead masthead-pagina text-white">
    <div class="masthead-content">
        <div class="container px-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-light mb-2">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Registrar préstamo</li>
                </ol>
            </nav>
            <h1 class="masthead-heading mb-1">Registrar préstamo</h1>
            <p class="masthead-lead mb-0">Completa los datos del socio y del libro para registrar un nuevo préstamo.</p>
        </div>
    </div>
    <?= circulos() ?>
</header>

<section class="container px-3 px-sm-4 px-lg-5 page-body">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">
            <div class="card form-card-bs">
                <div class="card-body p-4 p-md-5">
                    <form action="guardar.php" method="post" class="needs-validation" novalidate>
                        <?php require 'includes/form_prestamo.php'; ?>

                        <div class="d-flex flex-column flex-sm-row gap-3 mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill flex-fill">Guardar préstamo</button>
                            <a href="listar.php" class="btn btn-outline-dark btn-lg rounded-pill flex-fill">Ver listado</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require 'includes/footer.php'; ?>
