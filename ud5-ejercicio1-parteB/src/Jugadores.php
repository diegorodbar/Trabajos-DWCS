<?php

    namespace Tarea05;

    use PDO;
    use PDOException;

    // Clase Jugadores (extiende la clase conexión para conectarse a la base de datos)

    class Jugadores extends Conexion {

        // Atributos

        private $id;
        private $nombre;
        private $apellidos;
        private $dorsal;
        private $posicion;
        private $barcode;

        // Constructor

        public function __construct() {

            parent::__construct();

        }

        // Métodos específicos de la clase

        // Recuperar todos los jugadores de la base de datos

        function recuperarJugadores() {

            $consulta = "SELECT * FROM jugadores ORDER BY posicion, apellidos";

            $stmt = $this->conexion->prepare($consulta);

            try {

                $stmt->execute();

            } catch (PDOException $ex) {

                die("Error al recuperar jugadores: " . $ex->getMessage());

            }

            $this->conexion = null;

            return $stmt->fetchAll(PDO::FETCH_OBJ);

        }

        // Comprobar que ningún jugador tiene el dorsal que se pasa como parámetro

        function existeDorsal($d) {

            $consulta = "SELECT * FROM jugadores WHERE dorsal = :d";

            $stmt = $this->conexion->prepare($consulta);

            try {

                $stmt->execute([':d' => $d]);

            } catch (PDOException $ex) {

                die("Error al comprobar dorsal: " . $ex->getMessage());

            }

            if ($stmt->rowCount() == 0) {

                return false;

            } else { return true; }

        }

        // Comprobar que ningún jugador tiene el código de barras que se pasa como parámetro

        function existeBarcode($b) {

            $consulta = "SELECT * FROM jugadores WHERE dorsal = :b";

            $stmt = $this->conexion->prepare($consulta);

            try {

                $stmt->execute([':b' => $b]);

            } catch (PDOException $ex) {

                die("Error al comprobar código de barras: " . $ex->getMessage());

            }

            if ($stmt->rowCount() == 0) {

                return false;

            } else { return true; }

        }

        // Insertar jugadores en la base de datos

        function create() {

            $insert = "INSERT INTO jugadores (nombre, apellidos, dorsal, posicion, barcode) VALUES (:n, :a, :d, :p, :b);";

            $stmt = $this->conexion->prepare($insert);

            try {

                $stmt->execute([

                    ':n' => $this->nombre,
                    ':a' => $this->apellidos,
                    ':d' => $this->dorsal,
                    ':p' => $this->posicion,
                    ':b' => $this->barcode

                ]);

            } catch (PDOException $ex) {

                die("Error al insertar jugadores: " . $ex->getMessage());

            }

        }

        // Borrar todos los jugadores existentes en la base de datos

        function borrarTodo() {

            $borrado = "DELETE FROM jugadores;";

            $stmt = $this->conexion->prepare($borrado);

            try {

                $stmt->execute();

            } catch (PDOException $ex) {

                die("Error al borrar jugadores: " . $ex->getMessage());

            }

        }

        // Comprobar si ya existe algún jugador ya creado en la base de datos

        function tieneDatos() {

            $consulta = "SELECT * FROM jugadores;";

            $stmt = $this->conexion->prepare($consulta);

            try {

                $stmt->execute();

            } catch (PDOException $ex) {

                die("Error al comprobar si hay datos: " . $ex->getMessage());

            }

            if ($stmt->rowCount() == 0) {

                return false;

            } else { return true; }

        }

        // Setters

        public function setId($id) { $this->id = $id; }

        public function setNombre($nombre) { $this->nombre = $nombre; }

        public function setApellidos($apellidos) { $this->apellidos = $apellidos; }

        public function setDorsal($dorsal) { $this->dorsal = $dorsal; }

        public function setPosicion($posicion) { $this->posicion = $posicion; }

        public function setBarcode($barcode) { $this->barcode = $barcode; }

    }