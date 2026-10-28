<?php
require_once 'includes/seguridad.php';
require_once 'conexion.php';
require_once 'includes/helpers.php';

// Datos en vivo desde la base de datos
try {
    $resumen = $conn->query("SELECT COUNT(*) AS total,
            COUNT(DISTINCT codigo_socio) AS socios,
            COUNT(DISTINCT isbn) AS titulos,
            SUM(estado_prestamo IN ('Prestado', 'Atrasado')) AS activos,
            SUM(estado_prestamo = 'Devuelto') AS devueltos
            FROM prestamos")->fetch();
    $recientes = $conn->query("SELECT * FROM prestamos ORDER BY fecha_prestamo DESC, id_prestamo DESC LIMIT 4")->fetchAll();
} catch (PDOException $e) {
    errorBaseDatos($e);
}

// Colección destacada: ISBN, título, autor, género (para el filtro) y etiqueta
$destacados = [
    ['9788497592208', 'Cien años de soledad', 'Gabriel García Márquez', 'realismo', 'Realismo mágico'],
    ['9788408163435', 'La sombra del viento', 'Carlos Ruiz Zafón', 'fantasia', 'Misterio'],
    ['9788478884452', 'Harry Potter y la piedra filosofal', 'J. K. Rowling', 'fantasia', 'Fantasía'],
    ['9788437604572', 'Rayuela', 'Julio Cortázar', 'clasicos', 'Clásico'],
    ['9780307387264', 'El amor en los tiempos del cólera', 'Gabriel García Márquez', 'realismo', 'Romance'],
    ['9780802133908', 'Pedro Páramo', 'Juan Rulfo', 'clasicos', 'Clásico'],
    ['9781400034956', 'Crónica de una muerte anunciada', 'Gabriel García Márquez', 'realismo', 'Novela corta'],
    ['9780156012195', 'El principito', 'Antoine de Saint-Exupéry', 'infantil', 'Infantil'],
];
$generos = ['todos' => 'Todos', 'realismo' => 'Realismo mágico', 'clasicos' => 'Clásicos', 'fantasia' => 'Fantasía y misterio', 'infantil' => 'Infantil'];

$horario = [1 => ['Lunes', '7:00 – 20:00'], ['Martes', '7:00 – 20:00'], ['Miércoles', '7:00 – 20:00'],
            ['Jueves', '7:00 – 20:00'], ['Viernes', '7:00 – 20:00'], ['Sábado', '8:00 – 14:00'], ['Domingo', 'Cerrado']];
$hoy = (int) date('N');

// Fotos de las secciones, recortadas en cuadrado para el círculo de la plantilla
$foto = fn($id) => "https://images.unsplash.com/photo-$id?auto=format&fit=crop&w=700&h=700&q=70";

$titulo = 'Inicio';
$activo = 'inicio';
require 'includes/header.php';
?>

<!-- ============ PORTADA (masthead de la plantilla) ============ -->
<header class="masthead text-center text-white">
    <div class="masthead-content">
        <div class="container px-5">
            <h1 class="masthead-heading mb-0">Letras Vivas</h1>
            <h2 class="masthead-subheading mb-0">Tu biblioteca, a un clic</h2>

            <form action="listar.php" method="get" class="hero-buscador mx-auto mt-5" role="search">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="search" name="q" class="form-control" placeholder="Busca un libro, autor, socio o ISBN..." aria-label="Buscar préstamos">
                    <button class="btn btn-primary rounded-pill" type="submit">Buscar</button>
                </div>
            </form>

            <div class="d-flex flex-wrap justify-content-center gap-3 mt-5">
                <a class="btn btn-primary btn-xl rounded-pill" href="registrar.php">Registrar préstamo</a>
                <a class="btn btn-outline-light btn-xl rounded-pill" href="#como-funciona">Cómo funciona</a>
            </div>
        </div>
    </div>
    <?= circulos() ?>
</header>

<!-- ============ CINTA DE GÉNEROS ============ -->
<div class="cinta" aria-hidden="true">
    <div class="cinta-track">
        <?php for ($i = 0; $i < 2; $i++): ?>
            <span>Novela</span><i class="bi bi-circle-fill"></i><span>Poesía</span><i class="bi bi-circle-fill"></i>
            <span>Historia</span><i class="bi bi-circle-fill"></i><span>Ciencia</span><i class="bi bi-circle-fill"></i>
            <span>Realismo mágico</span><i class="bi bi-circle-fill"></i><span>Fantasía</span><i class="bi bi-circle-fill"></i>
            <span>Literatura infantil</span><i class="bi bi-circle-fill"></i><span>Clásicos latinoamericanos</span><i class="bi bi-circle-fill"></i>
        <?php endfor; ?>
    </div>
</div>

<!-- ============ NÚMEROS ============ -->
<section class="numeros">
    <div class="container px-5">
        <div class="row g-4 text-center">
            <?php
            $numeros = [
                [$resumen['total'], 'Préstamos registrados'],
                [$resumen['socios'], 'Socios lectores'],
                [$resumen['titulos'], 'Títulos en circulación'],
                [$resumen['devueltos'], 'Libros devueltos'],
            ];
            foreach ($numeros as $i => [$valor, $etiqueta]): ?>
                <div class="col-6 col-lg-3 reveal" style="transition-delay: <?= $i * 0.1 ?>s">
                    <div class="numero-valor" data-contador="<?= (int) $valor ?>"><?= (int) $valor ?></div>
                    <div class="numero-etiqueta"><?= $etiqueta ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============ CÓMO FUNCIONA (secciones alternadas de la plantilla) ============ -->
<div id="como-funciona">
    <div class="container px-5 pt-5 text-center reveal">
        <span class="section-kicker">¿Cómo funciona?</span>
        <h2 class="section-title">Tu préstamo en tres pasos</h2>
    </div>
    <?php
    $pasos = [
        ['1456513080510-7bf3a84b82f8', 'Registra', 'Llena el formulario con los datos del socio y del libro. El sistema los guarda en MySQL de forma segura con PDO.', 'registrar.php', 'Ir al formulario'],
        ['1507842217343-583bb7270b66', 'Consulta', 'Revisa todos los préstamos con búsqueda instantánea y filtros por estado: prestado, devuelto, atrasado o reservado.', 'listar.php', 'Ver el listado'],
        ['1519682337058-a94d519337bc', 'Actualiza', 'Cambia el estado o corrige los datos de un préstamo. El sistema confirma la edición y resalta el registro.', 'listar.php', 'Editar un préstamo'],
    ];
    foreach ($pasos as $i => [$imagen, $nombre, $texto, $enlace, $accion]):
        $imagenDerecha = $i % 2 === 0; // como en la plantilla, la imagen cambia de lado en cada sección ?>
        <section class="paso">
            <div class="container px-5">
                <div class="row gx-5 align-items-center">
                    <div class="col-lg-6 <?= $imagenDerecha ? 'order-lg-2' : '' ?> reveal">
                        <div class="p-5"><img class="img-fluid rounded-circle" src="<?= $foto($imagen) ?>" alt="<?= $nombre ?>"></div>
                    </div>
                    <div class="col-lg-6 <?= $imagenDerecha ? 'order-lg-1' : '' ?> reveal">
                        <div class="p-5">
                            <span class="section-kicker">Paso <?= $i + 1 ?></span>
                            <h2 class="display-4"><?= $nombre ?></h2>
                            <p><?= $texto ?></p>
                            <a href="<?= $enlace ?>" class="btn btn-primary rounded-pill btn-lg mt-2"><?= $accion ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endforeach; ?>
</div>

<!-- ============ COLECCIÓN DESTACADA ============ -->
<section id="coleccion" class="coleccion">
    <div class="container px-5">
        <div class="text-center mb-4 reveal">
            <span class="section-kicker">Colección destacada</span>
            <h2 class="section-title">Los libros que todos quieren leer</h2>
            <p class="section-sub mx-auto">Una selección de clásicos y favoritos de nuestros socios. Pasa el cursor sobre un libro para pedirlo prestado.</p>
        </div>

        <ul class="nav nav-pills nav-generos justify-content-center mb-5 reveal" id="generos">
            <?php foreach ($generos as $clave => $nombre): ?>
                <li class="nav-item"><button class="nav-link rounded-pill <?= $clave === 'todos' ? 'active' : '' ?>" data-genero="<?= $clave ?>"><?= $nombre ?></button></li>
            <?php endforeach; ?>
        </ul>

        <div class="row g-4 g-lg-5">
            <?php foreach ($destacados as $i => [$isbn, $libro, $autor, $genero, $etiqueta]):
                $pedir = 'registrar.php?' . http_build_query(['libro' => $libro, 'autor' => $autor, 'isbn' => $isbn]); ?>
                <div class="col-6 col-md-4 col-lg-3 libro-col reveal" data-genero="<?= $genero ?>" style="transition-delay: <?= ($i % 4) * 0.08 ?>s">
                    <div class="libro-card">
                        <div class="position-relative">
                            <?= portada($isbn, $libro, $autor) ?>
                            <div class="libro-overlay">
                                <a href="<?= htmlspecialchars($pedir) ?>" class="btn btn-primary btn-sm rounded-pill w-100">Pedir prestado</a>
                            </div>
                        </div>
                        <span class="badge badge-genero mt-3"><?= $etiqueta ?></span>
                        <h3 class="libro-nombre"><?= htmlspecialchars($libro) ?></h3>
                        <p class="libro-autor"><?= htmlspecialchars($autor) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============ CITA ============ -->
<section class="cita-banda text-center text-white">
    <div class="container px-5 reveal">
        <span class="comillas">“</span>
        <blockquote>Siempre imaginé que el Paraíso sería algún tipo de biblioteca.</blockquote>
        <p class="cita-autor">— Jorge Luis Borges</p>
    </div>
    <div class="bg-circle bg-circle-a"></div>
    <div class="bg-circle bg-circle-b"></div>
</section>

<!-- ============ ACTIVIDAD RECIENTE (solo con sesión: muestra nombres de socios) ============ -->
<?php if (usuarioActual() !== null): ?>
<section class="recent-section">
    <div class="container px-5">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-5 reveal">
            <div>
                <span class="section-kicker">Actividad reciente</span>
                <h2 class="section-title mb-0">Últimos préstamos</h2>
            </div>
            <a href="listar.php" class="btn btn-dark rounded-pill btn-lg">Ver todos</a>
        </div>
        <div class="row g-4">
            <?php foreach ($recientes as $i => $r): ?>
                <div class="col-md-6 col-xl-3 reveal" style="transition-delay: <?= $i * 0.08 ?>s">
                    <div class="prestamo-item h-100">
                        <?= portada($r['isbn'], $r['titulo_libro'], $r['autor_libro'], 'portada-sm') ?>
                        <div class="flex-grow-1 min-w-0">
                            <?= badgeEstado($r['estado_prestamo']) ?>
                            <h3 class="prestamo-libro"><?= htmlspecialchars($r['titulo_libro']) ?></h3>
                            <p class="small text-muted mb-2"><?= htmlspecialchars($r['autor_libro']) ?></p>
                            <div class="d-flex align-items-center gap-2 small">
                                <span class="avatar avatar-sm"><?= iniciales($r['nombre_socio']) ?></span>
                                <span class="text-truncate"><?= htmlspecialchars($r['nombre_socio']) ?></span>
                            </div>
                            <div class="small text-muted mt-2"><i class="bi bi-calendar3 me-1"></i><?= date('d/m/Y', strtotime($r['fecha_prestamo'])) ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (!$recientes): ?>
                <div class="col-12"><div class="alert alert-light border text-center mb-0">Aún no hay préstamos registrados.</div></div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ============ VISÍTANOS (sección alternada de la plantilla) ============ -->
<section id="visitanos" class="visitanos">
    <div class="container px-5">
        <div class="row gx-5 align-items-center">
            <div class="col-lg-6 reveal">
                <div class="p-5"><img class="img-fluid rounded-circle" src="<?= $foto('1481627834876-b7833e8f5570') ?>" alt="Sala de lectura de la biblioteca"></div>
            </div>
            <div class="col-lg-6 reveal">
                <div class="p-5">
                    <span class="section-kicker">Visítanos</span>
                    <h2 class="display-4">Un lugar para quedarse a leer</h2>
                    <p>Salas de lectura silenciosas, rincón infantil y wifi gratuito para todos los socios.</p>
                    <p class="direccion"><i class="bi bi-geo-alt-fill text-primary me-2"></i>Carrera 7 # 12-45, Centro · (601) 555 0198</p>

                    <div class="card horario-card my-4">
                        <div class="card-body p-4">
                            <h3 class="h6 text-uppercase fw-bold mb-3"><i class="bi bi-clock me-2 text-primary"></i>Horario de atención</h3>
                            <ul class="list-unstyled horario-lista mb-0">
                                <?php foreach ($horario as $n => [$dia, $horas]): ?>
                                    <li class="<?= $n === $hoy ? 'hoy' : '' ?>">
                                        <span><?= $dia ?><?= $n === $hoy ? ' <span class="badge rounded-pill text-bg-primary ms-1">Hoy</span>' : '' ?></span>
                                        <span><?= $horas ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <div class="accordion accordion-biblio" id="preguntas">
                        <?php
                        $preguntas = [
                            ['¿Quién puede pedir libros prestados?', 'Cualquier socio registrado. Solo necesitas tu código de socio y un correo de contacto.'],
                            ['¿Cuánto dura un préstamo?', 'Quince días calendario, con la opción de renovarlo una vez si nadie ha reservado el libro.'],
                            ['¿Qué pasa si me atraso?', 'El préstamo pasa al estado <strong>Atrasado</strong> y te avisamos por correo para que lo devuelvas lo antes posible.'],
                        ];
                        foreach ($preguntas as $i => [$pregunta, $respuesta]): ?>
                            <div class="accordion-item">
                                <h3 class="accordion-header">
                                    <button class="accordion-button <?= $i ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#p<?= $i ?>" aria-expanded="<?= $i ? 'false' : 'true' ?>" aria-controls="p<?= $i ?>"><?= $pregunta ?></button>
                                </h3>
                                <div id="p<?= $i ?>" class="accordion-collapse collapse <?= $i ? '' : 'show' ?>" data-bs-parent="#preguntas">
                                    <div class="accordion-body"><?= $respuesta ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============ LLAMADO A LA ACCIÓN ============ -->
<section class="cta-final text-center">
    <div class="container px-5 reveal">
        <h2 class="section-title">¿Ya elegiste tu próxima lectura?</h2>
        <p class="section-sub mx-auto">Registra el préstamo en segundos y mantén la colección en movimiento.</p>
        <a href="registrar.php" class="btn btn-primary btn-xl rounded-pill mt-4">Nuevo préstamo</a>
    </div>
</section>

<?php
$scripts = <<<'JS'
<script>
    // Filtro de la colección destacada por género
    document.querySelectorAll('#generos .nav-link').forEach(boton => {
        boton.addEventListener('click', () => {
            document.querySelectorAll('#generos .nav-link').forEach(b => b.classList.remove('active'));
            boton.classList.add('active');
            const genero = boton.dataset.genero;
            document.querySelectorAll('.libro-col').forEach(col => {
                col.classList.toggle('d-none', genero !== 'todos' && col.dataset.genero !== genero);
            });
        });
    });
</script>
JS;
require 'includes/footer.php';
?>
