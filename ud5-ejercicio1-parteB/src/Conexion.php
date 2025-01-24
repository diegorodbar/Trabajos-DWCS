<?php

namespace Tarea05;

use PDO;
use PDOException;

class Conexion {

    private $host;
    private $db;
    private $user;
    private $pass;
    private $dsn;
    protected $conexion;

    public function __construct() {

        $this->host = "localhost";   // Corrige el uso de '$'
        $this->db = "practicaUnidad5";  // Corrige el uso de '$'
        $this->user = "gestor";        // Corrige el uso de '$'
        $this->pass = "abc123.";      // Corrige el uso de '$'
        $this->dsn = "mysql:host={$this->host};dbname={$this->db};charset=utf8mb4";  // Corrige el uso de '$'
        $this->crearConexion();

    }

    public function crearConexion() {

        try {

            $this->conexion = new PDO($this->dsn, $this->user, $this->pass);  // Aquí también se usa '$this->'

            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $ex) {

            die("Error en la conexión: " . $ex->getMessage());

        }

        return $this->conexion;

    }

}
