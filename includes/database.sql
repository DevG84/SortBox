CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(50) NOT NULL
);

CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio_unitario DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL,
    fecha_ingreso DATE NOT NULL,
    activo BOOLEAN DEFAULT TRUE,
    id_categoria INT,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
);

CREATE TABLE clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    correo VARCHAR(100),
    direccion VARCHAR(150),
    fecha_registro DATE DEFAULT CURRENT_DATE
);

CREATE TABLE empleados (
    id_empleado INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    second_last_name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'supervisor', 'operator', 'viewer') NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE ventas (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_cliente INT,
    id_empleado INT,
    total DECIMAL(10,2) NOT NULL,
    metodo_pago VARCHAR(30),
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente),
    FOREIGN KEY (id_empleado) REFERENCES empleados(id_empleado)
);

CREATE TABLE detalle_venta (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_venta INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_venta) REFERENCES ventas(id_venta),
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

CREATE TABLE movimientos_inventario (
    id_movimiento INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    id_empleado INT NOT NULL,
    tipo ENUM('entrada', 'salida', 'ajuste') NOT NULL,
    cantidad INT NOT NULL,
    motivo VARCHAR(255) NOT NULL,
    fecha DATETIME NOT NULL,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto),
    FOREIGN KEY (id_empleado) REFERENCES empleados(id_empleado)
);

-- INSERCIONES

-- Categorías
INSERT INTO categorias (nombre_categoria) VALUES
('Bebidas'),
('Snacks'),
('Lácteos'),
('Panadería'),
('Limpieza'),
('Congelados'),
('Abarrotes'),
('Higiene Personal');


-- Productos
INSERT INTO productos (id_producto, nombre, descripcion, precio_unitario, stock, fecha_ingreso, id_categoria) VALUES
(1, 'Coca-Cola 600ml', 'Refresco embotellado', 18.50, 120, '2025-06-01', 1),
(2, 'Sabritas Clásicas 45g', 'Papas fritas con sal', 14.00, 80, '2025-06-02', 2),
(3, 'Leche Lala 1L', 'Leche entera pasteurizada', 22.00, 50, '2025-06-03', 3),
(4, 'Pan Bimbo Blanco', 'Pan de caja grande', 35.00, 40, '2025-06-05', 4),
(5, 'Cloralex 1L', 'Desinfectante multiusos', 27.00, 60, '2025-06-06', 5),
(6, 'Helado Napolitano 1L', 'Helado de tres sabores', 55.00, 30, '2025-06-10', 6),
(7, 'Frijoles Isadora 400g', 'Frijoles refritos en bolsa', 18.00, 100, '2025-06-11', 7),
(8, 'Shampoo Head & Shoulders 400ml', 'Shampoo anticaspa', 65.00, 45, '2025-06-12', 8),
(9, 'Galletas Marias 170g', 'Galletas clásicas', 15.00, 75, '2025-06-13', 2),
(10, 'Aceite Nutrioli 1L', 'Aceite vegetal', 42.00, 55, '2025-06-14', 7);

-- Clientes
INSERT INTO clientes (id_cliente, nombre, telefono, correo, direccion) VALUES
(1, 'María López', '5532114477', 'maria.lopez@example.com', 'Av. Reforma 123, CDMX'),
(2, 'Juan Pérez', '5522334455', 'juanp@example.com', 'Calle 5 de Mayo 456, CDMX'),
(3, 'Lucía González', '5544667788', 'lucia.g@example.com', 'Av. Insurgentes Sur 789, CDMX'),
(4, 'Roberto Díaz', '5566778899', 'roberto.d@example.com', 'Av. Universidad 400, CDMX'),
(5, 'Fernanda Ruiz', '5588991122', 'fer.ruiz@example.com', 'Calle Hidalgo 321, CDMX'),
(6, 'Luis Martínez', '5512345678', 'luis.m@example.com', 'Blvd. Aeropuerto 88, CDMX');

-- Empleados como usuarios
INSERT INTO empleados (id_empleado, username, first_name, last_name, second_last_name, password, role) VALUES
(1, 'carlos.r', 'Carlos', 'Ramírez', 'López', '1234hashed', 'operator'),
(2, 'ana.t', 'Ana', 'Torres', 'Gómez', '1234hashed', 'supervisor'),
(3, 'erika.s', 'Erika', 'Salgado', 'Martínez', '1234hashed', 'operator'),
(4, 'javier.t', 'Javier', 'Torres', 'Morales', '1234hashed', 'viewer'),
(5, 'Gio', 'Giovanni', 'Hernández', 'Cruz', '$2y$10$JsmIocpqRTlMdpe9MCz5XOGzVoip/jhiHEdCndTpEArfd9SuVtkX2', 'admin'),
(6, 'Abel', 'Jose Abel', 'Reyes', 'Castellanos', '$2y$10$JsmIocpqRTlMdpe9MCz5XOGzVoip/jhiHEdCndTpEArfd9SuVtkX2', 'admin');

-- Ventas (id_empleado actualizado según IDs anteriores)
INSERT INTO ventas (id_venta, fecha, id_cliente, id_empleado, total, metodo_pago) VALUES
(1, '2025-06-20 10:00:00', 1, 1, 51.00, 'Efectivo'),
(2, '2025-06-20 12:30:00', 2, 2, 71.00, 'Tarjeta'),
(3, '2025-06-21 09:45:00', 3, 1, 35.00, 'Efectivo'),
(4, '2025-06-21 11:00:00', 4, 3, 113.00, 'Tarjeta'),
(5, '2025-06-21 13:20:00', 5, 1, 60.00, 'Efectivo'),
(6, '2025-06-22 08:45:00', 6, 2, 107.00, 'Transferencia');

-- Detalles de venta
INSERT INTO detalle_venta (id_detalle, id_venta, id_producto, cantidad, precio_unitario) VALUES
(1, 1, 1, 2, 18.50),
(2, 1, 2, 1, 14.00),
(3, 2, 3, 2, 22.00),
(4, 2, 5, 1, 27.00),
(5, 3, 4, 1, 35.00),
(6, 4, 6, 2, 55.00),
(7, 4, 9, 1, 15.00),
(8, 5, 10, 1, 42.00),
(9, 5, 2, 1, 14.00),
(10, 6, 8, 1, 65.00);

-- Movimientos

INSERT INTO movimientos_inventario (id_producto, id_empleado, tipo, cantidad, motivo, fecha) VALUES
(3, 5, 'ajuste', 9, 'Caducado', '2025-06-21 16:51:00'),
(2, 3, 'salida', 10, 'Donación', '2025-06-20 12:38:00'),
(9, 6, 'ajuste', 3, 'Error de inventario', '2025-06-22 03:24:00'),
(4, 2, 'salida', 1, 'Robo', '2025-06-19 21:17:00'),
(10, 3, 'ajuste', 6, 'Caducado', '2025-06-15 18:29:00'),
(10, 1, 'entrada', 3, 'Compra a proveedor', '2025-06-18 12:36:00');
