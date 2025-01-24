-- Creamos la base de datos
create database practicaUnidad5;

-- Creamos el usuario
create user 'gestor'@'localhost' identified by 'abc123.';

-- Damos permisos al usuario
grant all on practicaUnidad5.* to gestor@'localhost';

-- Seleccionamos la base de datos
use practicaUnidad5;

 -- Creamos las Tablas --
 create table jugadores(
    id int auto_increment primary key,
    nombre varchar(40) not null,
    apellidos varchar(60) not null,
    dorsal int unique,
    posicion enum('Portero', 'Defensa', 'Lateral Izquierdo', 'Lateral Derecho', 'Central', 'Delantero'),
    barcode varchar(13) unique not null
 )

-- ## Insertamos Algunos datos, descomentar si no te ha funcionado  fazinotto/faker ##
insert into jugadores(nombre, apellidos, dorsal, posicion, barcode) values('Antonio','Gil Gil', 1, 1,'0952945303398');
insert into jugadores(nombre, apellidos, dorsal, posicion,  barcode) values('Ana','Hernandez Perez', 2, 2,'2406603743234');
insert into jugadores(nombre, apellidos, dorsal, posicion, barcode) values('Juan','Valdemoro Gil', 3, 2, '2829114057100');
insert into jugadores(nombre, apellidos, dorsal, posicion, barcode) values('Maria','Ruano Perez', 4, 2, '9745708466710');