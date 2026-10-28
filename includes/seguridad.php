<?php
// Seguridad común a todas las páginas: errores, cabeceras, sesión, login y token CSRF.
// Cada página la carga en su primera línea, antes de enviar cualquier HTML.

// Los errores se guardan en C:\xampp\apache\logs\error.log y nunca se muestran al visitante
ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

// Cabeceras de seguridad: ninguna otra web puede meter estas páginas en un iframe (clickjacking),
// el navegador no adivina tipos de archivo y no se anuncia la versión de PHP
header_remove('X-Powered-By');
header('X-Frame-Options: DENY');
header("Content-Security-Policy: frame-ancestors 'none'; base-uri 'self'; form-action 'self'; object-src 'none'");
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: same-origin');

// Sesión con cookie que JavaScript no puede leer y que no viaja en formularios enviados desde otros sitios
ini_set('session.use_strict_mode', '1');
session_name('letrasvivas');
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
    'secure'   => !empty($_SERVER['HTTPS']),
]);
session_start();

// Usuario con sesión iniciada, o null si nadie ha entrado
function usuarioActual(): ?string
{
    return $_SESSION['usuario'] ?? null;
}

// Corta la página si no hay sesión y manda al login, recordando a qué página quería ir
function requiereLogin(): void
{
    if (usuarioActual() === null) {
        $volver = 'listar.php';
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $consulta = $_SERVER['QUERY_STRING'] ?? '';
            $volver = basename($_SERVER['SCRIPT_NAME']) . ($consulta !== '' ? '?' . $consulta : '');
        }
        header('Location: login.php?volver=' . urlencode($volver));
        exit;
    }
    // Las páginas con datos de socios no se guardan en la caché del navegador
    header('Cache-Control: no-store');
}

// Token secreto de la sesión que deben traer todos los formularios POST
function tokenCsrf(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

// Campo oculto con el token, para imprimir dentro de cada <form method="post">
function campoCsrf(): string
{
    return '<input type="hidden" name="csrf" value="' . tokenCsrf() . '">';
}

// Rechaza la petición si el token no coincide: la envió otra página, no nuestro formulario
function verificarCsrf(): void
{
    $token = $_POST['csrf'] ?? '';
    if (!is_string($token) || !hash_equals(tokenCsrf(), $token)) {
        http_response_code(403);
        exit('Solicitud no válida. Vuelve a la página anterior, recárgala e inténtalo de nuevo.');
    }
}

// Guarda el detalle técnico en el log y muestra al visitante solo un mensaje genérico
function errorBaseDatos(PDOException $e): never
{
    error_log('Biblioteca: ' . $e->getMessage());
    http_response_code(500);
    exit('No se pudo acceder a la base de datos. Inténtalo más tarde.');
}
