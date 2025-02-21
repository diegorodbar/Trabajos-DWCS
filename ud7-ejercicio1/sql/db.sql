CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL
);

CREATE TABLE votos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cantidad INT DEFAULT 0,
    idPr INT NOT NULL,
    idUs INT NOT NULL,
    CONSTRAINT fk_votos_usu FOREIGN KEY (idUs) REFERENCES usuarios(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_votos_pro FOREIGN KEY (idPr) REFERENCES productos(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Insertar usuario administrador
INSERT INTO usuarios (nombre, email, password) 
VALUES ('Administrador', 'admin@email.com', MD5('abc123.'));

-- Insertar varios productos en la tabla "productos"
INSERT INTO productos (nombre, descripcion, precio) VALUES
('Camiseta de algodón', 'Camiseta 100% algodón, disponible en varios colores', 15.99),
('Pantalón vaquero', 'Pantalón vaquero clásico con ajuste cómodo', 29.99),
('Zapatillas deportivas', 'Zapatillas ideales para correr y entrenar', 49.99),
('Reloj inteligente', 'Reloj con funciones avanzadas y conectividad', 199.99);
