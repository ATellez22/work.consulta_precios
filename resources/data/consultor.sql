--
-- Table structure for table `articulos`
--

CREATE TABLE articulos (
   id SERIAL PRIMARY KEY,
   codigo VARCHAR(100) NOT NULL,
   descripcion VARCHAR(100) NOT NULL,
   precio INTEGER
);

INSERT INTO articulos (codigo, descripcion, precio) VALUES
(1000, 'PAN FELIPE', 2500),
(1001, 'PAN DE MOLDE', 3000),
(1002, 'PAN INTEGRAL', 3500),
(1003, 'PAN DE CENTENO', 4000),
(1004, 'PAN CIABATTA', 4500),
(1005, 'PAN BAGUETTE', 5000),
(1006, 'PAN DE AJO', 5500),
(1007, 'PAN DE HAMBURGUESA', 2000),
(1008, 'PAN DE HOT DOG', 2200),
(1009, 'PAN DE PASCUA', 6000);


/* En Mysql: ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci; */

/*
ENGINE: PostgreSQL no requiere especificar el motor de almacenamiento ya que solo tiene uno,
que es el propio PostgreSQL.

DEFAULT CHARSET: PostgreSQL usa codificaciones, y la codificación por defecto se puede establecer
a nivel de base de datos, no a nivel de tabla. La codificación por defecto suele ser UTF8.

COLLATE: PostgreSQL también permite establecer la colación (ordenación y comparación de texto)
a nivel de base de datos, columna o tabla. La colación predeterminada se basa en la configuración
regional del clúster de PostgreSQL.
*/
