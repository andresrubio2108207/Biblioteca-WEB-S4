<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Registrar prestamo</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="page-shell">
        <div class="form-card">
            <h1 class="page-title">Registrar prestamo</h1>

            <form action="guardar.php" method="post">
                <div class="form-grid">
                    <div class="form-field full-width">
                        <label for="estado_prestamo">Estado del prestamo</label>
                        <select id="estado_prestamo" name="estado_prestamo" required>
                            <option value="">Seleccione una opcion</option>
                            <option value="Prestado">Prestado</option>
                            <option value="Devuelto">Devuelto</option>
                            <option value="Atrasado">Atrasado</option>
                            <option value="Reservado">Reservado</option>
                        </select>
                    </div>

                    <div class="form-field">
                        <label for="codigo_socio">Codigo de socio</label>
                        <input type="text" id="codigo_socio" name="codigo_socio" required>
                    </div>

                    <div class="form-field">
                        <label for="nombre_socio">Nombre del socio</label>
                        <input type="text" id="nombre_socio" name="nombre_socio" required>
                    </div>

                    <div class="form-field">
                        <label for="titulo_libro">Titulo del libro</label>
                        <input type="text" id="titulo_libro" name="titulo_libro" required>
                    </div>

                    <div class="form-field">
                        <label for="autor_libro">Autor</label>
                        <input type="text" id="autor_libro" name="autor_libro" required>
                    </div>

                    <div class="form-field">
                        <label for="correo_socio">Correo del socio</label>
                        <input type="email" id="correo_socio" name="correo_socio" required>
                    </div>

                    <div class="form-field full-width">
                        <label for="isbn">ISBN</label>
                        <input type="text" id="isbn" name="isbn" required>
                    </div>

                    <div class="form-field full-width">
                        <label for="fecha_prestamo">Fecha del prestamo</label>
                        <input type="date" id="fecha_prestamo" name="fecha_prestamo" required>
                    </div>
                </div>

                <button type="submit">Guardar prestamo</button>
            </form>

            <div class="actions">
                <a class="btn-link" href="listar.php">Ver listado</a>
            </div>
        </div>
    </div>
</body>
</html>
