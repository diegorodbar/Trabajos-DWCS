<?php

// Clase abstracta base para todas las personas

abstract class Persona {

    // Atributos comunes para todas las personas

    protected $nombre;
    protected $apellido1;
    protected $apellido2;
    protected $fechaNacimiento;
    protected $dni;
    protected $direccion;
    protected $telefonos;
    protected $sexo;

    // Contador de objetos creados

    private static $contador = 0;

    // Constructor

    public function __construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo) {
        $this->nombre = $nombre;
        $this->apellido1 = $apellido1;
        $this->apellido2 = $apellido2;
        $this->fechaNacimiento = $fechaNacimiento;
        $this->dni = $dni;
        $this->direccion = $direccion;
        $this->telefonos = $telefonos;
        $this->sexo = $sexo;
        self::$contador++;
    }

    // Getters y Setters para los atributos comunes

    public function getNombre() { return $this->nombre; }

    public function setNombre($nombre) { $this->nombre = $nombre; }

    public function getApellido1() { return $this->apellido1; }

    public function setApellido1($apellido1) { $this->apellido1 = $apellido1; }

    public function getApellido2() { return $this->apellido2; }

    public function setApellido2($apellido2) { $this->apellido2 = $apellido2; }

    public function getFechaNacimiento() { return $this->fechaNacimiento; }

    public function setFechaNacimiento($fechaNacimiento) { $this->fechaNacimiento = $fechaNacimiento; }

    public function getDni() { return $this->dni; }

    public function setDni($dni) { $this->dni = $dni; }

    public function getDireccion() { return $this->direccion; }

    public function setDireccion($direccion) { $this->direccion = $direccion; }

    public function getTelefonos() { return $this->telefonos; }

    public function setTelefonos($telefonos) { $this->telefonos = $telefonos; }

    public function getSexo() { return $this->sexo; }

    public function setSexo($sexo) { $this->sexo = $sexo; }

    // Generar un objeto con datos aleatorios

    public static function generarAlAzar() {
        $nombres = ["Juan", "Ana", "Luis", "María"];
        $apellidos = ["Gómez", "Pérez", "López", "Rodríguez"];
        $sexos = ["M", "F"];
        $nombre = $nombres[array_rand($nombres)];
        $apellido1 = $apellidos[array_rand($apellidos)];
        $apellido2 = $apellidos[array_rand($apellidos)];
        $fechaNacimiento = rand(1980, 2010) . "-" . rand(1, 12) . "-" . rand(1, 28);
        $dni = "12345678" . chr(rand(65, 90));
        $direccion = "Calle Falsa 123";
        $telefonos = [rand(600000000, 699999999)];
        $sexo = $sexos[array_rand($sexos)];
        self::$contador++;

        return [
            'nombre' => $nombre,
            'apellido1' => $apellido1,
            'apellido2' => $apellido2,
            'fechaNacimiento' => $fechaNacimiento,
            'dni' => $dni,
            'direccion' => $direccion,
            'telefonos' => $telefonos,
            'sexo' => $sexo
        ];

    }

    // Método que devuelve el número de objetos creados

    public static function numeroObjetosCreado() { return self::$contador; }

    // Implementación de __toString

    public function __toString() { return "Nombre: $this->nombre $this->apellido1 $this->apellido2, DNI: $this->dni, Dirección: $this->direccion, Sexo: $this->sexo"; }

    // Método trabajar

    public function trabajar() { return "Soy una persona trabajando."; }

}

// Clase para Administrativos

class Administrativo extends Persona {

    // Atributos específicos

    protected $aniosServicio;
    private static $contador = 0;

    // Constructor

    public function __construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo, $aniosServicio) {
        parent::__construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo);
        $this->aniosServicio = $aniosServicio;
        self::$contador++;
    }

    // Getters y setters para los atributos específicos

    public function getAniosServicio() { return $this->aniosServicio; }

    public function setAniosServicio($aniosServicio) { $this->aniosServicio = $aniosServicio; }

    // Método heredado de la clase Persona para generar un objeto al azar

    public static function generarAlAzar() {

        $persona  = parent::generarAlAzar();
        $aniosServicio = rand(1, 40);

        return new Administrativo(
            $persona['nombre'],
            $persona['apellido1'],
            $persona['apellido2'],
            $persona['fechaNacimiento'],
            $persona['dni'],
            $persona['direccion'],
            $persona['telefonos'],
            $persona['sexo'],
            $aniosServicio
        );
    }

    // Método heredado de la clase Persona que devuelve el número de objetos creados

    public static function numeroObjetosCreado() { return self::$contador; }

    // Método para convertir a string los datos de un objeto

    public function __toString() { return parent::__toString() . ", Años de servicio: $this->aniosServicio"; }

    // Método para comprobar que la lógica se está aplicando correctamente para cada objeto

    public function trabajar() { return $this->sexo == "F" ? "Soy una administrativa y estoy gestionando tareas administrativas." : "Soy un administrativo y estoy gestionando tareas administrativas."; }
}

// Clase para Conserjes

class Conserje extends Persona {

    // Atributos específicos

    protected $aniosServicio;
    private static $contador = 0;

    // Constructor

    public function __construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo, $aniosServicio) {
        parent::__construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo);
        $this->aniosServicio = $aniosServicio;
        self::$contador++;
    }

    // Getters y setters para los atributos específicos

    public function getAniosServicio() { return $this->aniosServicio; }

    public function setAniosServicio($aniosServicio) { $this->aniosServicio = $aniosServicio; }

    // Generar un objeto con datos aleatorios

    public static function generarAlAzar() {
        $persona = parent::generarAlAzar();
        $aniosServicio = rand(1, 30); // Años de servicio aleatorio
        return new Conserje(
            $persona['nombre'],
            $persona['apellido1'],
            $persona['apellido2'],
            $persona['fechaNacimiento'],
            $persona['dni'],
            $persona['direccion'],
            $persona['telefonos'],
            $persona['sexo'],
            $aniosServicio
        );
    }

    // Método heredado de la clase Persona que devuelve el número de objetos creados

    public static function numeroObjetosCreado() { return self::$contador; }

    // Implementación de __toString

    public function __toString() { return parent::__toString() . ", Años de servicio: $this->aniosServicio"; }

    // Método trabajar

    public function trabajar() { return $this->sexo == "F" ? "Soy una conserje y estoy ayudando en el instituto." : "Soy un conserje y estoy ayudando en el instituto."; }

}

// Clase para Personal de Limpieza

class PersonalLimpieza extends Persona {

    // Atributos específicos

    protected $aniosServicio;
    private static $contador = 0;

    // Constructor

    public function __construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo, $aniosServicio) {
        parent::__construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo);
        $this->aniosServicio = $aniosServicio;
        self::$contador++;
    }

    // Getters y setters para los atributos específicos

    public function getAniosServicio() { return $this->aniosServicio; }

    public function setAniosServicio($aniosServicio) { $this->aniosServicio = $aniosServicio; }

    // Generar un objeto con datos aleatorios

    public static function generarAlAzar() {
        $persona = parent::generarAlAzar();
        $aniosServicio = rand(1, 30); // Años de servicio aleatorio
        return new PersonalLimpieza(
            $persona['nombre'],
            $persona['apellido1'],
            $persona['apellido2'],
            $persona['fechaNacimiento'],
            $persona['dni'],
            $persona['direccion'],
            $persona['telefonos'],
            $persona['sexo'],
            $aniosServicio
        );
    }

    // Método heredado de la clase Persona que devuelve el número de objetos creados

    public static function numeroObjetosCreado() { return self::$contador; }

    // Implementación de __toString

    public function __toString() { return parent::__toString() . ", Años de servicio: $this->aniosServicio"; }

    // Método trabajar

    public function trabajar() { return $this->sexo == "F" ? "Soy una trabajadora de limpieza y estoy limpiando en el instituto." : "Soy un trabajador de limpieza y estoy limpiando en el instituto."; }

}

// Clase para Profesorado

class Profesorado extends Persona {

    // Atributos específicos 

    protected $aniosServicio;
    protected $materias;
    protected $cargoDirectivo;
    private static $contador = 0;

    // Constructor

    public function __construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo, $aniosServicio, $materias, $cargoDirectivo) {
        parent::__construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo);
        $this->aniosServicio = $aniosServicio;
        $this->materias = $materias;
        $this->cargoDirectivo = $cargoDirectivo;
        self::$contador++;
    }

    // Getters y setters para los atributos específicos

    public function getAniosServicio() { return $this->aniosServicio; }

    public function setAniosServicio($aniosServicio) { $this->aniosServicio = $aniosServicio; }

    public function getMaterias() { return $this->materias; }

    public function setMaterias($materias) { $this->materias = $materias; }

    public function getCargoDirectivo() { return $this->cargoDirectivo; }

    public function setCargoDirectivo($cargoDirectivo) { $this->cargoDirectivo = $cargoDirectivo; }

    // Generar un objeto con datos aleatorios

    public static function generarAlAzar() {
        $persona = parent::generarAlAzar();
        $aniosServicio = rand(1, 40); // Años de servicio aleatorio
        $materias = [ ["Matemáticas", "Historia", "Lengua", "Ciencias"][array_rand(["Matemáticas", "Historia", "Lengua", "Ciencias"]) ] ];

        $cargoDirectivo = ["ninguno", "dirección", "secretariado", "jefatura estudios diurno", "jefatura estudios personas adultas", "vicedirección"][array_rand(["ninguno", "dirección", "secretariado", "jefatura estudios diurno", "jefatura estudios personas adultas", "vicedirección"])];
        return new Profesorado(
            $persona['nombre'],
            $persona['apellido1'],
            $persona['apellido2'],
            $persona['fechaNacimiento'],
            $persona['dni'],
            $persona['direccion'],
            $persona['telefonos'],
            $persona['sexo'],
            $aniosServicio,
            $materias,
            $cargoDirectivo
        );
    }

    // Método heredado de la clase Persona que devuelve el número de objetos creados

    public static function numeroObjetosCreado() { return self::$contador; }

    // Implementación de __toString

    public function __toString() {
        $materiasStr = implode(", ", $this->materias);
        return parent::__toString() . ", Años de servicio: $this->aniosServicio, Materias: $materiasStr, Cargo directivo: $this->cargoDirectivo";
    }

    // Método trabajar

    public function trabajar() {
        $mensaje = $this->sexo == "F" ? "Soy una profesora" : "Soy un profesor";
        return "$mensaje y estoy enseñando la/s materia/s: " . implode(", ", $this->materias) . ".";
    }

}

// Clase para Alumnado de ESO

class AlumnoESO extends Persona {

    // Atributos específicos 

    protected $curso;
    protected $grupo;
    private static $contador = 0;

    // Constructor

    public function __construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo, $curso, $grupo) {
        parent::__construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo);
        $this->curso = $curso;
        $this->grupo = $grupo;
        self::$contador++;
    }

    // Getters y setters para los atributos específicos

    public function getCurso() { return $this->curso; }

    public function getGrupo() { return $this->grupo; }

    public function setCurso($curso) { $this->curso = $curso; }

    public function setGrupo($grupo) { $this->grupo = $grupo; }

    // Método heredado de la clase Persona para generar un objeto al azar

    public static function generarAlAzar() {
        $persona = parent::generarAlAzar();
        $curso = rand(1, 4);
        $grupo = chr(rand(65, 68)); // Grupos A-D
        return new AlumnoESO(
            $persona['nombre'],
            $persona['apellido1'],
            $persona['apellido2'],
            $persona['fechaNacimiento'],
            $persona['dni'],
            $persona['direccion'],
            $persona['telefonos'],
            $persona['sexo'],
            $curso, 
            $grupo
        );
    }

    // Método heredado de la clase Persona que devuelve el número de objetos creados

    public static function numeroObjetosCreado() { return self::$contador; }

    // Método para convertir a string los datos de un objeto

    public function __toString() { return parent::__toString() . ", Curso: $this->curso, Grupo: $this->grupo"; }

    // Método para comprobar que la lógica se está aplicando correctamente para cada objeto

    public function trabajar() { return $this->sexo == "F" ? "Soy una estudiante de la ESO y estoy estudiando." : "Soy un estudiante de la ESO y estoy estudiando."; }

}

// Clase para Alumnado de Bachillerato

class AlumnoBachillerato extends Persona {

    // Atributos específicos 

    protected $curso;
    protected $grupo;
    private static $contador = 0;

    // Constructor

    public function __construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo, $curso, $grupo) {
        parent::__construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo);
        $this->curso = $curso;
        $this->grupo = $grupo;
        self::$contador++;
    }

    // Getters y setters para los atributos específicos

    public function getCurso() { return $this->curso; }

    public function getGrupo() { return $this->grupo; }

    public function setCurso($curso) { $this->curso = $curso; }

    public function setGrupo($grupo) { $this->grupo = $grupo; }

    // Generar un objeto con datos aleatorios

    public static function generarAlAzar() {
        $persona = parent::generarAlAzar();
        $curso = rand(1, 2); // Cursos típicos de Bachillerato (1 o 2)
        $grupo = chr(rand(65, 67)); // Grupos como A, B, C
        return new AlumnoBachillerato(
            $persona['nombre'],
            $persona['apellido1'],
            $persona['apellido2'],
            $persona['fechaNacimiento'],
            $persona['dni'],
            $persona['direccion'],
            $persona['telefonos'],
            $persona['sexo'],
            $curso, 
            $grupo
        );
    }

    // Método heredado de la clase Persona que devuelve el número de objetos creados

    public static function numeroObjetosCreado() { return self::$contador; }    

    // Implementación de __toString

    public function __toString() { return parent::__toString() . ", Curso: $this->curso, Grupo: $this->grupo"; }

    // Método trabajar

    public function trabajar() {
        $mensaje = $this->sexo == "F" ? "Soy una estudiante" : "Soy un estudiante";
        return "$mensaje de Bachillerato y estoy estudiando en el curso $this->curso, grupo $this->grupo.";
    }
}

// Clase para Alumnado de Formación Profesional

class AlumnoFP extends Persona {

    // Atributos específicos 

    protected $cicloFormativo;
    protected $curso;
    protected $grupo;
    private static $contador = 0;

    // Constructor

    public function __construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo, $cicloFormativo, $curso, $grupo) {
        parent::__construct($nombre, $apellido1, $apellido2, $fechaNacimiento, $dni, $direccion, $telefonos, $sexo);
        $this->cicloFormativo = $cicloFormativo;
        $this->curso = $curso;
        $this->grupo = $grupo;
        self::$contador++;
    }

    // Getters y setters para los atributos específicos

    public function getCicloFormativo() { return $this->cicloFormativo; }

    public function setCicloFormativo($cicloFormativo) { $this->cicloFormativo = $cicloFormativo; }

    public function getCurso() { return $this->curso; }

    public function getGrupo() { return $this->grupo; }

    public function setCurso($curso) { $this->curso = $curso; }

    public function setGrupo($grupo) { $this->grupo = $grupo; }

    // Método heredado de la clase Persona que devuelve el número de objetos creados

    public static function numeroObjetosCreado() { return self::$contador; }  

    // Generar un objeto con datos aleatorios

    public static function generarAlAzar() {
        $persona = parent::generarAlAzar();

        $materias = ["Matemáticas", "Historia", "Lengua", "Ciencias"][array_rand(["Matemáticas", "Historia", "Lengua", "Ciencias"])];
        
        $cicloFormativo = ["Administración y Finanzas", "Desarrollo de Aplicaciones Web", "Sistemas Electrotécnicos y Automáticos", "Mantenimiento Electrónico", "Educación Infantil"][array_rand(["Administración y Finanzas", "Desarrollo de Aplicaciones Web", "Sistemas Electrotécnicos y Automáticos", "Mantenimiento Electrónico", "Educación Infantil"])];
        $curso = rand(1, 2); // 1er o 2do curso de FP
        $grupo = chr(rand(65, 67)); // Grupos como A, B, C
        return new AlumnoFP(
            $persona['nombre'],
            $persona['apellido1'],
            $persona['apellido2'],
            $persona['fechaNacimiento'],
            $persona['dni'],
            $persona['direccion'],
            $persona['telefonos'],
            $persona['sexo'],
            $cicloFormativo, 
            $curso, 
            $grupo
        );
    }

    // Implementación de __toString

    public function __toString() { return parent::__toString() . ", Ciclo Formativo: $this->cicloFormativo, Curso: $this->curso, Grupo: $this->grupo"; }

    // Método trabajar

    public function trabajar() {
        $mensaje = $this->sexo == "F" ? "Soy una estudiante" : "Soy un estudiante";
        return "$mensaje de Formación Profesional en el ciclo $this->cicloFormativo y estoy en el curso $this->curso, grupo $this->grupo.";
    }
}

// Script para crear 100 objetos aleatorios generando un número aleatorio para determinar la clase (1: Administrativo, 2: Profesorado, 3: Conserje, etc.)

for ($i = 0; $i < 100; $i++) {

    $claseAleatoria = rand(1, 7); //
    
    switch ($claseAleatoria) {
        case 1:
            $admin = Administrativo::generarAlAzar();
            echo $admin->__toString() . PHP_EOL;
            echo $admin->trabajar() . PHP_EOL;
            echo "\n";
            break;
        case 2:
            $prof = Profesorado::generarAlAzar();
            echo $prof->__toString() . PHP_EOL;
            echo $prof->trabajar() . PHP_EOL;
            echo "\n";
            break;
        case 3:
            $cons = Conserje::generarAlAzar();
            echo $cons->__toString() . PHP_EOL;
            echo $cons->trabajar() . PHP_EOL;
            echo "\n";
            break;
        case 4:
            $eso = AlumnoESO::generarAlAzar();
            echo $eso->__toString() . PHP_EOL;
            echo $eso->trabajar() . PHP_EOL;
            echo "\n";
            break;
        case 5:
            $bach = AlumnoBachillerato::generarAlAzar();
            echo $bach->__toString() . PHP_EOL;
            echo $bach->trabajar() . PHP_EOL;
            echo "\n"; 
            break;
        case 6:
            $fp = AlumnoFP::generarAlAzar();
            echo $fp->__toString() . PHP_EOL;
            echo $fp->trabajar() . PHP_EOL;
            echo "\n";
            break;
        case 7:
            $limp = PersonalLimpieza::generarAlAzar();
            echo $limp->__toString() . PHP_EOL;
            echo $limp->trabajar() . PHP_EOL;
            echo "\n";
            break;
    }
}

echo "Objetos de Administrativo creados: " . Administrativo::numeroObjetosCreado() . PHP_EOL;
echo "Objetos de Profesorado creados: " . Profesorado::numeroObjetosCreado() . PHP_EOL;
echo "Objetos de Conserje creados: " . Conserje::numeroObjetosCreado() . PHP_EOL;
echo "Objetos de AlumnoESO creados: " . AlumnoESO::numeroObjetosCreado() . PHP_EOL;
echo "Objetos de AlumnoBachillerato creados: " . AlumnoBachillerato::numeroObjetosCreado() . PHP_EOL;
echo "Objetos de AlumnoFP creados: " . AlumnoFP::numeroObjetosCreado() . PHP_EOL;
echo "Objetos de PersonalLimpieza creados: " . PersonalLimpieza::numeroObjetosCreado() . PHP_EOL;

// Pruebas realizadas previamente

// Crear un Administrativo aleatorio. OK.

// $admin = Administrativo::generarAlAzar();
// echo $admin->__toString() . PHP_EOL;
// echo $admin->trabajar() . PHP_EOL;
// echo "Objetos de Administrativo creados: " . Administrativo::numeroObjetosCreado() . PHP_EOL;

// Crear un Personal de Limpieza aleatorio. OK.

// $limpieza = PersonalLimpieza::generarAlAzar();
// echo $limpieza->__toString() . PHP_EOL;
// echo $limpieza->trabajar() . PHP_EOL;
// echo "Objetos de Personal de Limpieza creados: " . PersonalLimpieza::numeroObjetosCreado() . PHP_EOL;

// Crear un Conserje aleatorio. OK.

// $conserje = Conserje::generarAlAzar();
// echo $conserje->__toString() . PHP_EOL;
// echo $conserje->trabajar() . PHP_EOL;
// echo "Objetos de Conserje creados: " . Conserje::numeroObjetosCreado() . PHP_EOL;

// Crear 2 Alumnos de la FP aleatorios. OK.

// $alumnoFP1 = AlumnoFP::generarAlAzar();
// echo $alumnoFP1->__toString() . PHP_EOL;
// echo $alumnoFP1->trabajar() . PHP_EOL;
// echo "Objetos de AlumnoFP creados: " . AlumnoFP::numeroObjetosCreado() . PHP_EOL;

// $alumnoFP2 = AlumnoFP::generarAlAzar();
// echo $alumnoFP2->__toString() . PHP_EOL;
// echo $alumnoFP2->trabajar() . PHP_EOL;
// echo "Objetos de AlumnoFP creados: " . AlumnoFP::numeroObjetosCreado() . PHP_EOL;

// Crear un Alumno de la ESO aleatorio. OK.

// $alumno = AlumnoESO::generarAlAzar();
// echo $alumno->__toString() . PHP_EOL;
// echo $alumno->trabajar() . PHP_EOL;
// echo "Objetos de AlumnoESO creados: " . AlumnoESO::numeroObjetosCreado() . PHP_EOL;

// Crear un Alumno de Bachillerato. OK.

// $alumnoBachillerato = AlumnoBachillerato::generarAlAzar();
// echo $alumnoBachillerato->__toString() . PHP_EOL;
// echo $alumnoBachillerato->trabajar() . PHP_EOL;
// echo "Objetos de AlumnoBachillerato creados: " . AlumnoBachillerato::numeroObjetosCreado() . PHP_EOL;

// Crear un Profesor aleatorio. OK.

// $profesor = Profesorado::generarAlAzar();
// echo $profesor->__toString() . PHP_EOL;
// echo $profesor->trabajar() . PHP_EOL;
// echo "Objetos de Profesorado creados: " . Profesorado::numeroObjetosCreado() . PHP_EOL;

?>