//
// Table structure for table `articulos`
//

CREATE TABLE articulos (
   id SERIAL PRIMARY KEY,
   codigo VARCHAR(100) NOT NULL,
   descripcion VARCHAR(100) NOT NULL,
   precio INTEGER,
   fecha_lote VARCHAR(100) NOT NULL,
   fecha_venc VARCHAR(100) NOT NULL
);

INSERT INTO articulos (codigo, descripcion, precio, fecha_lote, fecha_venc) VALUES
(1000, 'PAN FELIPE', 2500, '2024/07/01', '2024/07/20'),
(1001, 'PAN DE MOLDE', 3000, '2024/07/02', '2024/07/22'),
(1002, 'PAN INTEGRAL', 3500, '2024/07/03', '2024/07/23'),
(1003, 'PAN DE CENTENO', 4000, '2024/07/04', '2024/07/24'),
(1004, 'PAN CIABATTA', 4500, '2024/07/05', '2024/07/25'),
(1005, 'PAN BAGUETTE', 5000, '2024/07/06', '2024/07/26'),
(1006, 'PAN DE AJO', 5500, '2024/07/07', '2024/07/27'),
(1007, 'PAN DE HAMBURGUESA', 2000, '2024/07/08', '2024/07/28'),
(1008, 'PAN DE HOT DOG', 2200, '2024/07/09', '2024/07/29'),
(1009, 'PAN DE PASCUA', 6000, '2024/07/10', '2024/07/30');


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
