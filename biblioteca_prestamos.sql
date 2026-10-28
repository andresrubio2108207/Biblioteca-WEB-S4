-- Base de datos: biblioteca_prestamos

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `biblioteca_prestamos` DEFAULT CHARACTER SET utf8mb4;
USE `biblioteca_prestamos`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `id_prestamo` int NOT NULL,
  `estado_prestamo` varchar(15) NOT NULL,
  `codigo_socio` varchar(15) NOT NULL,
  `nombre_socio` varchar(40) NOT NULL,
  `titulo_libro` varchar(80) NOT NULL,
  `autor_libro` varchar(40) NOT NULL,
  `correo_socio` varchar(60) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `fecha_prestamo` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Datos de ejemplo para la tabla `prestamos`
--

INSERT INTO `prestamos` (`id_prestamo`, `estado_prestamo`, `codigo_socio`, `nombre_socio`, `titulo_libro`, `autor_libro`, `correo_socio`, `isbn`, `fecha_prestamo`) VALUES
(1, 'Prestado', 'SOC-001', 'Laura Jimenez', 'Cien años de soledad', 'Gabriel Garcia Marquez', 'laura.jimenez@correo.com', '9780307474728', '2026-09-01'),
(2, 'Devuelto', 'SOC-014', 'Andres Rubio', 'El principito', 'Antoine de Saint-Exupery', 'andres.rubio@correo.com', '9780156012195', '2026-08-20'),
(3, 'Atrasado', 'SOC-027', 'Camila Torres', '1984', 'George Orwell', 'camila.torres@correo.com', '9780451524935', '2026-07-15');

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`id_prestamo`);

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id_prestamo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

COMMIT;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios` (personal que inicia sesión)
--

CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `usuario` varchar(30) NOT NULL,
  `clave_hash` varchar(255) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Usuario inicial `admin`; la contraseña se guarda cifrada con password_hash() de PHP
INSERT INTO `usuarios` (`usuario`, `clave_hash`) VALUES ('admin', '$2y$10$Lc4UXZfKudXD3Sx82gOhQuhcykdFl7X2P7l24ZF/g8PPEOMIQwFbm');

--
-- Usuario de MySQL de la aplicación (ver conexion.php), con los permisos mínimos
--

CREATE USER IF NOT EXISTS 'biblioteca_app'@'localhost' IDENTIFIED BY 'mL7FdKDWFqVcL7rTCx1Jzt3V';
GRANT SELECT, INSERT, UPDATE, DELETE ON `biblioteca_prestamos`.`prestamos` TO 'biblioteca_app'@'localhost';
GRANT SELECT ON `biblioteca_prestamos`.`usuarios` TO 'biblioteca_app'@'localhost';
