# SISTEMA DE REGISTROS DE EMPLEADOS Y ASISTENCIAS

## Descripcion del negocio
Nombre: Norkys SAC <br>
Norky's es una empresa que se dedica a la venta de pollo a la brasa. Cuenta con una cadena de restaurantes de comida rápida distribuidos en varias ciudades del Perú. Su especialidad es el pollo a la brasa, que acompañan con papas fritas, ensalada fresca y diferentes cremas. Además de atender en sus locales, también ofrecen servicio de delivery y recojo en tienda para mayor comodidad de los clientes.

## Identificar el problema y solución
En Norky's existe un problema principal: la falta de un registro adecuado de los empleados. Actualmente, no llevan un control formal que les permita saber quiénes trabajan, qué horarios cumplen, cuántas horas han laborado o si han faltado algún día. Esto provoca problemas como demoras en el pago de salarios, asignación incorrecta de turnos, dificultad para calcular horas extras y confusión al momento de gestionar vacaciones o permisos. En resumen, al no tener un registro organizado del personal, la administración del restaurante se vuelve desordenada y propensa a errores. la solucion que planteo es: Es que para solucionar este problema, se propone desarrollar e implementar un sistema informático de registro de empleados diseñado específicamente para las necesidades de Norky's. Este sistema permitirá llevar un control formal y automatizado de toda la información del personal, incluyendo datos personales, asistencia diaria, turno de trabajo, horas extras y faltas. Con esta herramienta, la administración podrá acceder rápidamente a reportes organizados, reducir errores en los salarios, asignar turnos de manera eficiente y mantener un orden general en la gestión del personal. De esta forma, el sistema busca eliminar el desorden administrativo y minimizar los errores que actualmente afectan el funcionamiento del restaurante.

# Preanálisis
Necesidades

Actualmente, Norky's necesita un sistema que le permita registrar y controlar a todos sus empleados de forma ordenada. Las principales necesidades son:

Llevar un control diario de la asistencia de cada trabajador (entrada, salida, faltas y tardanzas).
Registrar los horarios y turnos asignados a cada empleado.
Calcular de forma automática las horas extras trabajadas.
Calcular los pagos de salarios de manera rápida y sin errores.
Generar reportes claros sobre la situación del personal.
Tener toda la información de los empleados en un solo lugar, accesible para la administración.

# Estudio de viabilidad

Viabilidad técnica:
 El sistema se puede desarrollar con herramientas tecnológicas disponibles y accesibles. No se requiere equipos demasiado avanzados, solo computadoras con conexión básica y un servidor o una base de datos local. Es factible de implementar.

Viabilidad económica:
 El costo de desarrollo e implementación del sistema es razonable y se recuperaría a mediano plazo al reducir los errores en pagos y el tiempo perdido en tareas administrativas. Además, evita posibles multas o problemas legales por falta de control del personal.

Viabilidad operativa:
El personal administrativo de Norky's puede aprender a usar el sistema con una capacitación básica. El sistema será sencillo e intuitivo, por lo que no debería generar resistencia al cambio. Al contrario, facilitará su trabajo diario.

## Alcance del sistema

El sistema se encargará de:

Registrar, modificar y eliminar datos de los empleados (nombre, cargo, turno, etc.).
Controlar la asistencia diaria con registro de horarios de entrada y salida.
Calcular automáticamente las horas trabajadas, horas extras, tardanzas y faltas.
Calcular el pago mensual de cada empleado según sus horas trabajadas y beneficios.
Generar reportes como: lista de empleados, asistencias mensuales, empleados faltistas, etc.

El sistema no incluirá:

El manejo de nóminas contables avanzadas o integración con sistemas contables externos.
El control de inventarios o ventas del restaurante (solo gestión de personal).
Una aplicación móvil (inicialmente solo versión para computadora).

 # Análisis

 ## Requisitos funcionales:

- El sistema debe permitir registrar un nuevo empleado ingresando su DNI, nombres, apellidos, salario, celular, cargo y  turno asignado.
- El sistema debe leer el código del DNI del empleado mediante un sensor o lector de código de barras.
- Al pasar el DNI por el sensor, el sistema debe registrar automáticamente la hora de entrada o salida del empleado.
- El sistema debe identificar si el empleado ya registró su entrada o salida para evitar duplicados.
- El sistema debe calcular automáticamente las horas trabajadas, horas extras, tardanzas y faltas de cada empleado.
- El sistema debe calcular el pago mensual de cada empleado en base a sus horas trabajadas.
- El sistema debe generar reportes como: asistencia diaria, asistencia mensual, empleados faltistas, y lista de empleados.
- El sistema debe permitir buscar empleados por su nombre o por su número de DNI.
- El sistema debe permitir modificar o eliminar datos de empleados cuando sea necesario.

 ## Requisitos no funcionales:

- El sistema debe ser fácil de usar, con una interfaz sencilla e intuitiva para el personal administrativo.
- El sistema debe registrar la asistencia en menos de 3 segundos después de pasar el DNI por el sensor.
- El sistema debe ser confiable, evitando pérdida de datos o registros duplicados.
- El sistema debe funcionar en computadoras con recursos básicos (Windows 7 o superior, 4GB de RAM).
- El sistema debe tener un respaldo automático o manual de los datos para evitar pérdidas.
- El sistema debe ser seguro, permitiendo el acceso solo al personal autorizado (por ejemplo, con contraseña de administrador).
- El sistema debe funcionar correctamente incluso sin conexión a internet (local).

  # IMAGEN DEL NEGOCIO
![imagen](imagenes/)

## Stack Tecnológico

| Capa | Tecnología |
|---|---|
| **Backend** | PHP 8+ — POO (Programación Orientada a Objetos) — MVC desde cero |
| **Base de datos** | MariaDB — PDO (PHP Data Objects) con prepared statements |
| **Frontend** | HTML5, CSS3, JavaScript — Vistas PHP con layouts reutilizables |
| **Servidor web** | Apache — Reescritura de URLs vía `.htaccess` |
| **Control de versiones** | Git + GitHub |
| **Configuración** | Variables de entorno (`.env`) para credenciales |
---

## Arquitectura del Proyecto

El sistema aplica **POO** y **MVC** implementado desde cero. Los 4 pilares de POO en el proyecto:

### Flujo de una Petición


### Estructura del Proyecto

## Instalación

### Requisitos previos
- PHP 8+
- Apache con `mod_rewrite` habilitado (XAMPP recomendado)
- MariaDB / MySQL

### Pasos

```bash
# 1. Clonar el repositorio
git clone https://github.com/ojitoslanda/employee-attendance-system.git
cd employee-attendance-system

# 2. Configurar variables de entorno
cp .env.example .env
# Editar .env con tus credenciales de base de datos

# 3. Crear la base de datos


# 4. Apuntar el servidor web a la carpeta public/

```

## TRELLO

### DIAGRAMA DE FIGMA UI/UX

## Base de datos
```sql
create database senai_asistencia;
use senai_asistencia;


create table cargo (
id_cargo int auto_increment primary key,
nombre_cargo varchar(50) not null
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

create table empleado(
id_empleado int primary key auto_increment,
nombre varchar(100) not null,
apellido varchar(100) not null,
dni varchar(8) unique not null,
celular varchar(20),
correo varchar (100) not null unique,
id_cargo int not null,
fecha_registro timestamp default current_timestamp,
foreign key (id_cargo) references cargo(id_cargo)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

create table usuario(
id_usuario int auto_increment primary key,
roles enum('admin', 'superadmin') default 'admin',
nombre_usuario varchar (150) not null,
clave varchar(250) not null
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

create table asistencia(
id_asistencia int auto_increment primary key,
fecha date not null,
hora_entrada timestamp default current_timestamp not null,
hora_salida timestamp default current_timestamp not null,
estado enum('asistio', 'tardanza', 'falto') default 'falto' not null,
id_empleado int not null,
foreign key (id_empleado) references empleado(id_empleado)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

### Diagrama Entidad-Relacion (DER)

 
### Modelo Relacional (MR)
![MODELO_RELACIONAL](https://raw.githubusercontent.com/ojitoslanda/testing/refs/heads/master/img/db.png)

### Cardinalidades
