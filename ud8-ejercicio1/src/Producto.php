<?php
    
    require_once 'Conexion.php'; // Incluir la clase de conexión a la base de datos.

    // Definición de la clase Producto que extiende de la clase Conexion para heredar la conexión a la BD.
    class Producto extends Conexion {
        
        // Propiedades privadas que representan los atributos de un producto.
        private $id;             // Identificador único del producto.
        private $nombre;         // Nombre del producto.
        private $nombre_corto;   // Nombre corto o abreviado del producto.
        private $pvp;            // Precio de venta al público.
        private $familia;        // Familia o categoría a la que pertenece el producto.
        private $descripcion;    // Descripción detallada del producto.

        // Constructor de la clase Producto. Llama al constructor de la clase padre (Conexion) para asegurar que la conexión a la BD esté establecida.
        public function __construct() {
            parent::__construct();
        }

        // Métodos getter y setter para cada propiedad.

        public function getId() { return $this->id; }
        public function setId($id) { $this->id = $id; }
        
        public function getNombre() { return $this->nombre; }
        public function setNombre($nombre) { $this->nombre = $nombre; }

        public function getNombreCorto() { return $this->nombre_corto; }
        public function setNombreCorto($nombreCorto) { $this->nombre_corto = $nombreCorto; }
        
        public function getPvp() { return $this->pvp; }
        public function setPvp($pvp) { $this->pvp = $pvp; }
        
        public function getFamilia() { return $this->familia; }
        public function setFamilia($familia) { $this->familia = $familia; }
        
        public function getDescripcion() { return $this->descripcion; }
        public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }

        // Método para obtener el listado de productos de la base de datos.
        public function listadoProductos() {
            
            $query = "SELECT * FROM productos ORDER BY nombre, familia"; // Definir la consulta SQL para seleccionar todos los productos, ordenados por nombre y familia.
            
            $stmt = self::$conexion->prepare($query); // Preparar la consulta utilizando la conexión heredada de la clase Conexion.
            try {
                
                $stmt->execute(); // Ejecutar la consulta preparada.
            } catch (PDOException $e) { exit("Error al recuperar los productos: " . $e->getMessage()); } // En caso de error, detener la ejecución y mostrar un mensaje de error.
            
            return $stmt; // Retornar el objeto statement que contiene el resultado de la consulta.
        }

    }

?>