<?php

    // Definición de la clase Conexion para gestionar la conexión a la base de datos
    class Conexion {
        
        protected static $conexion; // Variable estática protegida que almacenará la instancia de la conexión

        // Constructor de la clase que se ejecuta al instanciar la clase Conexion
        public function __construct() {
            
            if (self::$conexion === null) { self::crearConexion(); } // Si no se ha creado aún la conexión, se llama al método para crearla

        }

        // Método estático para crear y configurar la conexión a la base de datos
        public static function crearConexion() {
            
            // Credenciales y nombre de la base de datos
            $usuario   = "gestor";
            $clave     = "abc123.";
            $baseDatos = "tarea08";
            
            $dsn = "mysql:host=localhost;dbname={$baseDatos};charset=utf8mb4"; // Construir el DSN (Data Source Name) que define la conexión a la base de datos

            try {
                self::$conexion = new PDO($dsn, $usuario, $clave); // Crear una nueva instancia de PDO utilizando el DSN y las credenciales
                self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configurar PDO para que lance excepciones en caso de error
            } catch (PDOException $e) { exit("Error en la conexión: " . $e->getMessage()); } // Si ocurre un error al intentar conectar, detener la ejecución y mostrar un mensaje de error
        }

    }

?>