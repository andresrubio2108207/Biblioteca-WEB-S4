<?php
require_once 'includes/seguridad.php';
require_once 'conexion.php';

// Solo se vuelve a páginas propias del sitio, nunca a otro dominio
$volver = $_POST['volver'] ?? $_GET['volver'] ?? 'listar.php';
if (!is_string($volver) || !preg_match('/^[a-z_]+\.php(\?\S*)?$/', $volver)) {
    $volver = 'listar.php';
}

if (usuarioActual() !== null) {
    header('Location: ' . $volver);
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificarCsrf();
    $usuario = is_string($_POST['usuario'] ?? null) ? trim($_POST['usuario']) : '';
    $clave = is_string($_POST['clave'] ?? null) ? $_POST['clave'] : '';
    try {
        $stmt = $conn->prepare("SELECT clave_hash FROM usuarios WHERE usuario = :usuario");
        $stmt->execute([':usuario' => $usuario]);
        $hash = $stmt->fetchColumn();
    } catch (PDOException $e) {
        errorBaseDatos($e);
    }
    if ($hash && password_verify($clave, $hash)) {
        session_regenerate_id(true); // sesión nueva al entrar: evita la fijación de sesión
        $_SESSION['usuario'] = $usuario;
        unset($_SESSION['csrf']);
        header('Location: ' . $volver);
        exit;
    }
    sleep(1); // frena los programas que prueban muchas contraseñas seguidas
    $error = 'Usuario o contraseña incorrectos.';
}

$titulo = 'Iniciar sesión';
$activo = 'login';
require 'includes/header.php';
?>

<header class="masthead masthead-pagina text-white">
    <div class="masthead-content">
        <div class="container px-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-light mb-2">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Iniciar sesión</li>
                </ol>
            </nav>
            <h1 class="masthead-heading mb-1">Iniciar sesión</h1>
            <p class="masthead-lead mb-0">Acceso para el personal de la biblioteca que gestiona los préstamos.</p>
        </div>
    </div>
    <?= circulos() ?>
</header>

<section class="container px-3 px-sm-4 px-lg-5 page-body">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card form-card-bs">
                <div class="card-body p-4 p-md-5">
                    <?php if ($error): ?>
                        <div class="alert alert-danger d-flex align-items-center gap-2" role="alert"><i class="bi bi-x-octagon-fill"></i><?= htmlspecialchars($error) ?></div>
                    <?php elseif (isset($_GET['salio'])): ?>
                        <div class="alert alert-success d-flex align-items-center gap-2" role="alert"><i class="bi bi-check-circle-fill"></i>Cerraste sesión correctamente.</div>
                    <?php endif; ?>
                    <form action="login.php" method="post" class="needs-validation" novalidate>
                        <?= campoCsrf() ?>
                        <input type="hidden" name="volver" value="<?= htmlspecialchars($volver) ?>">
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="usuario" name="usuario" maxlength="30" autocomplete="username" required autofocus>
                                <div class="invalid-feedback">Ingresa tu usuario.</div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="clave" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control" id="clave" name="clave" autocomplete="current-password" required>
                                <div class="invalid-feedback">Ingresa tu contraseña.</div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill w-100 mt-4">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require 'includes/footer.php'; ?>
