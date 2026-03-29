CREATE DATABASE IF NOT EXISTS wildpet;
USE wildpet;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    slug VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    imagen VARCHAR(255),
    stock INT DEFAULT 0
);

ALTER TABLE productos ADD COLUMN categoria_id INT,
ADD CONSTRAINT fk_productos_categorias FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL;

CREATE TABLE carrito (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    producto_id INT,
    cantidad INT DEFAULT 1,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
);

CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    total DECIMAL(10,2) NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE pedido_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    producto_id INT,
    precio_unitario DECIMAL(10,2) NOT NULL,
    cantidad INT NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL
);

CREATE TABLE usuario_facturacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    nif VARCHAR(20),
    direccion VARCHAR(255),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE usuario_envio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    direccion VARCHAR(255),
    ciudad VARCHAR(100),
    codigo_postal VARCHAR(10),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE INDEX idx_productos_categoria ON productos(categoria_id);
CREATE INDEX idx_usuarios_email ON usuarios(email);

-- Fin del esquema relacional de la plataforma WildPet v1.0
