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
![imagen](imagenes/imagen_norkys.avif)

