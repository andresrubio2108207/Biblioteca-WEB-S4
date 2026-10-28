<?php
// Cabecera compartida: recibe $titulo (título de la pestaña) y $activo (enlace activo del menú)
require_once __DIR__ . '/seguridad.php';
require_once __DIR__ . '/helpers.php';
$titulo = $titulo ?? 'Biblioteca Letras Vivas';
$activo = $activo ?? '';
// Plantilla base: Start Bootstrap "One Page Wonder" (incluye Bootstrap 5.2.3 con los colores del tema)
$plantilla = 'startbootstrap-one-page-wonder-gh-pages';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= htmlspecialchars($titulo) ?> | Letras Vivas</title>
    <!-- Fuentes de la plantilla, servidas desde el sitio (Google Fonts no permite el atributo integrity) -->
    <link href="<?= recurso('fuentes/fuentes.css') ?>" rel="stylesheet">
    <!-- Estilos de la plantilla (incluye Bootstrap) -->
    <link href="<?= recurso($plantilla . '/css/styles.css') ?>" rel="stylesheet">
    <!-- integrity: el navegador rechaza el archivo si el CDN lo entrega modificado -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" integrity="sha384-XGjxtQfXaH2tnPFa9x+ruJTuLE3Aa6LhHSWRr1XeTyhezb4abCG4ccI5AkVDxqC+" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= recurso('estilo.css') ?>">
    <!-- Sin scripts en línea: la política CSP solo permite archivos .js del propio sitio -->
    <script src="<?= recurso('js/temprano.js') ?>"></script>
</head>
<body id="page-top">
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom navbar-biblio fixed-top">
        <div class="container px-5">
            <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
                <i class="bi bi-book-half text-primary"></i>Letras Vivas
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Abrir menú"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link <?= $activo === 'inicio' ? 'active' : '' ?>" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#coleccion">Colección</a></li>
                    <li class="nav-item"><a class="nav-link <?= $activo === 'registrar' ? 'active' : '' ?>" href="registrar.php">Registrar préstamo</a></li>
                    <li class="nav-item"><a class="nav-link <?= $activo === 'listar' ? 'active' : '' ?>" href="listar.php">Listado</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#visitanos">Visítanos</a></li>
                    <?php if (usuarioActual() !== null): ?>
                        <li class="nav-item">
                            <form action="logout.php" method="post" class="m-0">
                                <?= campoCsrf() ?>
                                <button type="submit" class="nav-link" title="Sesión de <?= htmlspecialchars(usuarioActual()) ?>"><i class="bi bi-box-arrow-right me-1"></i>Salir</button>
                            </form>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link <?= $activo === 'login' ? 'active' : '' ?>" href="login.php"><i class="bi bi-person-lock me-1"></i>Entrar</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <main>
