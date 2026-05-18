-- Archivo de inicialización de base de datos para WildPet
-- Ejecuta este script en phpMyAdmin o en la terminal MySQL

CREATE DATABASE IF NOT EXISTS wildpet;
USE wildpet;

-- Tabla de categorías
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);

-- Tabla de productos
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    imagen VARCHAR(255),
    stock INT NOT NULL DEFAULT 0,
    id_categoria INT NOT NULL,
    destacado BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id)
);

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('cliente','admin') DEFAULT 'cliente'
);

-- Tabla de carrito
CREATE TABLE IF NOT EXISTS carrito (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_producto) REFERENCES productos(id)
);

-- Tabla de pedidos
CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente','pagado','enviado','entregado') DEFAULT 'pendiente',
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

-- Tabla de detalle de pedido
CREATE TABLE IF NOT EXISTS detalle_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id),
    FOREIGN KEY (id_producto) REFERENCES productos(id)
);

-- Insertar categorías
INSERT INTO categorias (nombre) VALUES
('Perros'),
('Gatos'),
('Pájaros'),
('Peces');

-- Insertar productos para Perros (id_categoria = 1)
INSERT INTO productos (nombre, descripcion, precio, imagen, stock, id_categoria, destacado) VALUES
('Juguete Masticable para Cachorros', 'Juguete resistente para el desarrollo dental y diversión.', 12.50, 'img2/d1.png', 20, 1, TRUE),
('Alimento Seco Premium para Perros', 'Receta rica en proteínas para una vida activa.', 35.99, 'img2/d2.png', 15, 1, TRUE),
('Collar de Cuero Ajustable', 'Diseño duradero y cómodo para uso diario.', 24.99, 'img2/d3.png', 10, 1, FALSE),
('Dispensador Automático de Golosinas', 'Ideal para entrenar y recompensar a tu perro.', 49.00, 'img2/d4.png', 8, 1, FALSE),
('Cepillo de Aseo Suave', 'Mantiene el pelaje brillante y sin enredos.', 18.75, 'img2/d5.png', 12, 1, FALSE),
('Cama Ortopédica para Perros', 'Máxima comodidad para un descanso reparador.', 75.00, 'img2/d6.png', 5, 1, FALSE),
('Correa Retráctil Robusta', 'Control seguro y libertad para tu paseo.', 29.95, 'img2/d7.png', 7, 1, FALSE),
('Kit de Entrenamiento para Perros', 'Herramientas esencial para una buena educación.', 22.00, 'img2/d8.png', 9, 1, FALSE),
('Botella de Agua Portátil', 'Hidratación fácil durante las aventuras al aire libre.', 15.49, 'img2/d9.png', 14, 1, FALSE),
('Arnés Antitirones Premium', 'Ayuda a controlar los tirones y mejora el paseo.', 38.20, 'img2/d10.png', 6, 1, FALSE),
('Set de Juguetes de Cuerda', 'Diversión interactiva para horas de juego.', 19.99, 'img2/d11.png', 11, 1, FALSE),
('Champú Hipoalergénico', 'Suave para la piel sensible, deja el pelaje brillante.', 14.00, 'img2/d12.png', 16, 1, FALSE),
('Abrigo Impermeable para Perros', 'Protege a tu perro de la lluvia y el viento.', 45.00, 'img2/d13.png', 8, 1, FALSE),
('Bozal de Malla Transpirable', 'Seguro y cómodo para el control y la socialización.', 20.30, 'img2/d14.png', 10, 1, FALSE),
('Juguete Dispensador de Premios', 'Estimula la mente apto para jugar.', 16.99, 'img2/d15.png', 13, 1, FALSE);

-- Insertar usuario de prueba (contraseña: 123456)
INSERT INTO usuarios (nombre, email, password, rol) VALUES
('Usuario Prueba', 'prueba@wildpet.com', '$2y$10$YJGbLVq5ApRq5tqTqPjKu.9VzQQe8D9G9Zvj0pZvp3K9QKYVpJ9gS', 'cliente');

-- Nota: La contraseña del usuario de prueba es "123456" hasheada con bcrypt
-- Para crear más usuarios, usa la función password_hash() de PHP o crea un archivo de registro.
