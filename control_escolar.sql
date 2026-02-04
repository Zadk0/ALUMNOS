CREATE DATABASE control_escolar;
USE control_escolar;

CREATE TABLE carreras(
 id INT AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(150) NOT NULL
);

INSERT INTO carreras(nombre) VALUES
('Administración de Empresas'),
('Administración de Empresas Turísticas'),
('Relaciones Internacionales'),
('Contaduría Pública y Finanzas'),
('Derecho'),
('Mercadotecnia y Publicidad'),
('Gastronomía'),
('Periodismo y Ciencias de la Comunicación'),
('Diseño de Modas'),
('Pedagogía'),
('Cultura Física y Educación del Deporte'),
('Idiomas ( Inglés y Francés )'),
('Psicología'),
('Diseño de Interiores'),
('Diseño Gráfico'),
('Ingeniería en Logística y Transporte'),
('Ingeniero Arquitecto'),
('Informática Administrativa y Fiscal'),
('Ingeniería en Sistemas Computacionales'),
('Ingeniería Mecánica Automotriz');

CREATE TABLE grupos(
 id INT AUTO_INCREMENT PRIMARY KEY,
 carrera_id INT,
 turno VARCHAR(50),
 grado VARCHAR(20),
 grupo VARCHAR(10),
 FOREIGN KEY (carrera_id) REFERENCES carreras(id)
);

CREATE TABLE alumnos(
 id INT AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(100),
 apellido_p VARCHAR(100),
 apellido_m VARCHAR(100),
 grupo_id INT,
 FOREIGN KEY (grupo_id) REFERENCES grupos(id)
);
