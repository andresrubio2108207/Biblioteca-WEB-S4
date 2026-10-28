<?php
// Devuelve la insignia (badge) de Bootstrap que corresponde a cada estado de préstamo
function badgeEstado(string $estado): string
{
    $mapa = [
        'Prestado'  => ['estado-prestado', 'bi-arrow-up-right-circle'],
        'Devuelto'  => ['estado-devuelto', 'bi-check2-circle'],
        'Atrasado'  => ['estado-atrasado', 'bi-exclamation-octagon'],
        'Reservado' => ['estado-reservado', 'bi-bookmark-star'],
    ];
    [$clase, $icono] = $mapa[$estado] ?? ['bg-secondary', 'bi-question-circle'];
    return '<span class="badge badge-estado ' . $clase . '"><i class="bi ' . $icono . ' me-1"></i>'
        . htmlspecialchars($estado) . '</span>';
}

// Portada real del libro (Open Library, buscada por ISBN). Si no existe imagen,
// queda visible una portada dibujada con CSS que muestra el título y el autor.
function portada(string $isbn, string $titulo, string $autor = '', string $clase = ''): string
{
    $tono = 'tono-' . (abs(crc32($titulo)) % 5 + 1);
    $url = 'https://covers.openlibrary.org/b/isbn/' . rawurlencode($isbn) . '-M.jpg?default=false';
    return '<div class="portada ' . $clase . ' ' . $tono . '">'
        . '<div class="portada-respaldo"><span class="portada-titulo">' . htmlspecialchars($titulo) . '</span>'
        . '<span class="portada-autor">' . htmlspecialchars($autor) . '</span></div>'
        . '<img src="' . $url . '" alt="Portada de ' . htmlspecialchars($titulo) . '" loading="lazy" onerror="this.remove()">'
        . '</div>';
}

// Círculos decorativos del encabezado (masthead) de la plantilla One Page Wonder
function circulos(): string
{
    return '<div class="bg-circle-1 bg-circle"></div><div class="bg-circle-2 bg-circle"></div>'
        . '<div class="bg-circle-3 bg-circle"></div><div class="bg-circle-4 bg-circle"></div>';
}

// Iniciales del socio para el avatar de la tabla, ya escapadas para imprimir en HTML
function iniciales(string $nombre): string
{
    $partes = preg_split('/\s+/', trim($nombre));
    $ini = mb_substr($partes[0] ?? '', 0, 1) . mb_substr($partes[1] ?? '', 0, 1);
    return htmlspecialchars(mb_strtoupper($ini));
}

// Estados que puede tener un préstamo
const ESTADOS = ['Prestado', 'Devuelto', 'Atrasado', 'Reservado'];

// Lee los campos del formulario de préstamo enviados por POST
function datosFormulario(): array
{
    $campos = ['estado_prestamo', 'codigo_socio', 'nombre_socio', 'titulo_libro',
               'autor_libro', 'correo_socio', 'isbn', 'fecha_prestamo'];
    $datos = [];
    foreach ($campos as $campo) {
        $valor = $_POST[$campo] ?? '';
        $datos[':' . $campo] = is_string($valor) ? trim($valor) : '';
    }
    return $datos;
}

// Revisa en el servidor los datos de datosFormulario(); devuelve los errores (vacío si todo está bien)
function validarPrestamo(array $d): array
{
    $errores = [];
    $largos = [
        ':codigo_socio' => ['El código de socio', 15],
        ':nombre_socio' => ['El nombre del socio', 40],
        ':titulo_libro' => ['El título', 80],
        ':autor_libro'  => ['El autor', 40],
        ':correo_socio' => ['El correo', 60],
        ':isbn'         => ['El ISBN', 20],
    ];
    foreach ($largos as $campo => [$nombre, $maximo]) {
        $largo = mb_strlen($d[$campo]);
        if ($largo === 0 || $largo > $maximo) {
            $errores[] = "$nombre es obligatorio y admite hasta $maximo caracteres.";
        }
    }
    if (!in_array($d[':estado_prestamo'], ESTADOS, true)) {
        $errores[] = 'El estado no es válido.';
    }
    if (!filter_var($d[':correo_socio'], FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El correo no es válido.';
    }
    if (!preg_match('/^(\d{9}[\dX]|\d{13})$/i', str_replace(['-', ' '], '', $d[':isbn']))) {
        $errores[] = 'El ISBN debe tener 10 o 13 dígitos.';
    }
    $fecha = DateTime::createFromFormat('!Y-m-d', $d[':fecha_prestamo']);
    if (!$fecha || $fecha->format('Y-m-d') !== $d[':fecha_prestamo']) {
        $errores[] = 'La fecha no es válida.';
    }
    return $errores;
}
