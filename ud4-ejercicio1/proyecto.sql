-- Crear base de datos 'proyecto'
CREATE DATABASE proyecto;

USE proyecto;

-- Crear tabla familias
CREATE TABLE familias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(255) NOT NULL
);

-- Crear tabla productos
CREATE TABLE productos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(255) NOT NULL,
  nombre_corto VARCHAR(255) NOT NULL,
  precio DECIMAL(10, 2) NOT NULL,
  familia_id INT NOT NULL,
  descripcion TEXT,
  FOREIGN KEY (familia_id) REFERENCES familias(id)
);

-- Insertar algunos ejemplos de familias
INSERT INTO familias (nombre) VALUES
('Cámaras digitales'),
('Consolas'),
('Equipos Multifunción'),
('Impresoras'),
('Libros electrónicos'),
('Memorias Flash'),
('Netbooks'),
('Ordenadores'),
('Ordenadores portátiles'),
('Reproductores MP3'),
('Routers'),
('Sistemas de alimentación ininterrumpida'),
('Software'),
('Televisores'),
('Videocámaras');

-- Crear un nuevo usuario para la aplicación
CREATE USER 'proyecto'@'localhost' IDENTIFIED BY 'abc123.';

-- Asignar privilegios al nuevo usuario para la base de datos 'proyecto'
GRANT ALL PRIVILEGES ON proyecto.* TO 'proyecto'@'localhost';

-- Aplicar los cambios realizados
FLUSH PRIVILEGES;