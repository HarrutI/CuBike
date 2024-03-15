-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS CuBike;

-- Usar la base de datos
USE CuBike;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    passsword VARCHAR(50) NOT NULL,
    roles ENUM('admin', 'user', 'banned') NOT NULL DEFAULT 'user',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Crear la tabla estacion
CREATE TABLE IF NOT EXISTS estacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    capacidad INT NOT NULL,
    ubicacion VARCHAR(100) NOT NULL
);
-- Tabla de bicicletas
-- Tabla de bicicletas
CREATE TABLE IF NOT EXISTS bicicletas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(50) NOT NULL,
    propietario_id INT,
    estacion_id INT,
    hora_entrada TIMESTAMP
);

-- Agregar clave foránea a la tabla de bicicletas
ALTER TABLE bicicletas
ADD CONSTRAINT fk_estacion_id
FOREIGN KEY (estacion_id) REFERENCES estacion(id);


