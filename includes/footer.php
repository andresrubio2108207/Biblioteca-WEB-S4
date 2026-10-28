    </main>
    <!-- Pie de página -->
    <footer class="footer-biblio py-5 bg-black">
        <div class="container px-5 text-center">
            <a class="footer-marca" href="index.php"><i class="bi bi-book-half text-primary me-2"></i>Letras Vivas</a>
            <ul class="footer-links">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="index.php#coleccion">Colección</a></li>
                <li><a href="registrar.php">Registrar</a></li>
                <li><a href="listar.php">Listado</a></li>
                <li><a href="listar.php?q=Atrasado">Atrasados</a></li>
                <li><a href="index.php#visitanos">Visítanos</a></li>
            </ul>
            <p class="footer-contacto">
                <span><i class="bi bi-geo-alt"></i>Carrera 7 # 12-45, Centro</span>
                <span><i class="bi bi-telephone"></i>(601) 555 0198</span>
                <span><i class="bi bi-envelope"></i>contacto@letrasvivas.edu.co</span>
            </p>
            <div class="d-flex justify-content-center gap-2 mb-4">
                <a href="#" class="social" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="social" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="social" aria-label="X"><i class="bi bi-twitter-x"></i></a>
                <a href="#" class="social" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
            </div>
            <p class="m-0 text-white small">Copyright &copy; Biblioteca Letras Vivas <?= date('Y') ?> · Hecho con PHP, MySQL y Bootstrap 5</p>
        </div>
    </footer>
    <!-- JS de Bootstrap (misma versión que la plantilla) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <!-- Formularios, animaciones y contadores comunes a todas las páginas -->
    <script src="<?= recurso('js/sitio.js') ?>"></script>
    <?php // $script: archivo .js propio de la página (por ejemplo js/listar.js) ?>
    <?php if (!empty($script)): ?>
        <script src="<?= recurso($script) ?>"></script>
    <?php endif; ?>
</body>
</html>
