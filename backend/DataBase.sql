
    -- Crear la base de datos
    CREATE DATABASE IF NOT EXISTS UnderBike;

    -- Usar la base de datos
    USE UnderBike;

    -- Tabla de usuarios
    CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(50) NOT NULL,
        apellido VARCHAR(50) NOT NULL,
        password VARCHAR(50) NOT NULL,
        email VARCHAR(50) NOT NULL UNIQUE,
        roles ENUM('admin', 'user', 'banned') NOT NULL DEFAULT 'user',
        fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        fecha_subscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    -- Crear la tabla estacion
    CREATE TABLE IF NOT EXISTS estacion (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(50) NOT NULL,
        capacidad INT NOT NULL,
        ubicacion VARCHAR(100) NOT NULL
    );
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
    
